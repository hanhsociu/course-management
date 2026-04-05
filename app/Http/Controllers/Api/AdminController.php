<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Helper: Lấy dữ liệu thống kê dùng chung cho cả Web và API
     */
    private function getStatsData()
    {
        $totalStudents = User::where('role', 'user')->count();
        $totalCourses = Course::count();
        $totalEnrollments = DB::table('enrollments')->count();

        $totalRevenue = DB::table('enrollments')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->sum('courses.price');

        $recentEnrollments = DB::table('enrollments')
            ->join('users', 'enrollments.user_id', '=', 'users.id')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->select('users.name as student_name', 'courses.title as course_title', 'enrollments.created_at')
            ->orderBy('enrollments.created_at', 'desc')
            ->limit(5)
            ->get();

        return [
            'total_students'     => $totalStudents,
            'total_courses'      => $totalCourses,
            'total_enrollments'  => $totalEnrollments,
            'total_revenue'      => number_format($totalRevenue, 0, ',', '.') . ' VNĐ',
            'recent_enrollments' => $recentEnrollments
        ];
    }

    // ==========================================
    // PHẦN API (Cho Mobile hoặc React/Vue sau này)
    // ==========================================

    public function dashboard()
    {
        return response()->json([
            'success' => true,
            'data'    => $this->getStatsData()
        ]);
    }

    // ==========================================
    // PHẦN WEB (Dành cho giao diện Blade)
    // ==========================================

    /**
     * Trang Dashboard Admin
     */
    public function indexWeb()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Bảo vệ: Chỉ Admin mới được vào đây
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('catalog')->with('error', 'Bạn không có quyền truy cập Admin!');
        }

        $stats = (object) $this->getStatsData(); // Ép kiểu sang object để gọi $stats->total_students
        return view('dashboard', compact('stats'));
    }

    /**
     * Quản lý khóa học (Admin)
     */
    public function coursesWeb()
    {
        $courses = Course::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Quản lý bài học (Admin)
     */
    public function lessonsWeb(Course $course)
    {
        return view('admin.lessons.index', compact('course'));
    }

    /**
     * Phòng học (Học viên) - Đã thêm Bảo mật & Tiến độ
     */
    public function learningView(Course $course, ?Lesson $lesson = null)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // CHỐT CHẶN: Phải là Admin HOẶC đã mua khóa học mới được vào
        if (!$user->isAdmin() && !$user->enrolledCourses->contains($course->id)) {
            return redirect()->route('courses.show', $course->id)
                ->with('error', 'Bạn cần đăng ký khóa học này để vào học.');
        }

        // Lấy danh sách bài học và xác định bài đang xem
        $lessons = $course->lessons()->orderBy('order', 'asc')->get();
        if (!$lesson) {
            $lesson = $lessons->first();
        }

        // Tính toán tiến độ %
        $totalLessons = $lessons->count();
        $completedCount = $user->completedLessons()
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->count();
        $progress = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;

        return view('learning.index', compact('course', 'lesson', 'lessons', 'progress', 'completedCount', 'totalLessons'));
    }

    /**
     * Danh sách khóa học công khai (Trang chủ)
     */
    public function catalogView(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:255',
        ]);

        $courses = Course::query()
            ->published()
            ->search($request->input('q'))
            ->withExists(['lessons as has_preview_lessons' => function ($q) {
                $q->where('is_preview', true);
            }])
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        $this->loadEnrolledCoursesForAuthUser();

        return view('welcome', compact('courses'));
    }

    /**
     * Chi tiết khóa học (Trước khi đăng ký)
     */
    public function courseDetail(Course $course)
    {
        $lessons = $course->lessons()->orderBy('order', 'asc')->get();
        $previewLessons = $lessons->where('is_preview', true)->values();
        $lockedCount = $lessons->count() - $previewLessons->count();

        $this->loadEnrolledCoursesForAuthUser();

        return view('courses.show', compact('course', 'lessons', 'previewLessons', 'lockedCount'));
    }

    /**
     * Xem thử bài học (công khai — chưa cần đăng ký / thanh toán)
     */
    public function previewLearningView(Course $course, ?Lesson $lesson = null)
    {
        $previewLessons = $course->lessons()
            ->where('is_preview', true)
            ->orderBy('order')
            ->get();

        if ($previewLessons->isEmpty()) {
            return redirect()->route('courses.show', $course)
                ->with('info', 'Khóa học này chưa có bài học thử. Xem nội dung tổng quan bên dưới và đăng ký khi sẵn sàng.');
        }

        if ($lesson === null) {
            $lesson = $previewLessons->first();
        } elseif ($lesson->course_id !== (int) $course->id || !$lesson->is_preview) {
            abort(404);
        }

        $totalInCourse = $course->lessons()->count();

        return view('learning.preview', [
            'course' => $course,
            'lesson' => $lesson,
            'previewLessons' => $previewLessons,
            'totalInCourse' => $totalInCourse,
        ]);
    }

    /**
     * Xử lý đăng ký khóa học (miễn phí / admin). Khóa trả phí dùng PayOS checkout.
     */
    public function enroll(Course $course)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để đăng ký.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->enrolledCourses->contains($course->id)) {
            return redirect()->route('learning.view', $course)
                ->with('info', 'Bạn đã đăng ký khóa học này.');
        }

        if ($user->isAdmin() || (float) $course->price <= 0) {
            $user->enrolledCourses()->syncWithoutDetaching([$course->id]);

            return redirect()->route('learning.view', $course->id)
                ->with('message', 'Đăng ký thành công! Bắt đầu học ngay thôi.');
        }

        return redirect()->route('courses.show', $course)
            ->with('error', 'Khóa học có phí — vui lòng thanh toán bằng PayOS.');
    }

    /**
     * Khóa học của tôi (Dashboard học viên)
     */
    public function myCourses()
    {
        if (!Auth::check()) return redirect()->route('login');

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $courses = $user->enrolledCourses()->with('lessons')->get();

        // Tính tiến độ cho từng khóa học
        foreach ($courses as $course) {
            $total = $course->lessons->count();
            $done = $user->completedLessons()
                ->whereIn('lesson_id', $course->lessons->pluck('id'))
                ->count();
            $course->progress = $total > 0 ? round(($done / $total) * 100) : 0;
        }

        return view('my-courses', compact('courses'));
    }

    private function loadEnrolledCoursesForAuthUser(): void
    {
        $user = Auth::user();
        if ($user instanceof User) {
            $user->loadMissing('enrolledCourses');
        }
    }
}
