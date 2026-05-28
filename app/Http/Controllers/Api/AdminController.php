<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\CoursePayment;
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
        $totalStudents = User::where('role', User::ROLE_STUDENT)->count();
        $totalCourses = Course::count();
        $totalEnrollments = DB::table('enrollments')->count();

        // Doanh thu chỉ lấy từ giao dịch đã thanh toán thành công.
        $totalRevenue = CoursePayment::query()
            ->where('status', 'paid')
            ->sum('amount');

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
     * Khóa học của tôi (Dashboard học viên)
     */
    public function myCourses()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $courses = $user->enrolledCourses()
            ->with(['category', 'instructor'])
            ->get();

        foreach ($courses as $course) {
            $course->progress = (int) ($course->pivot->progress_percent ?? 0);
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
