<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * API Dashboard: Thống kê số liệu
     */
    public function dashboard()
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

        return response()->json([
            'success' => true,
            'data' => [
                'total_students'     => $totalStudents,
                'total_courses'      => $totalCourses,
                'total_enrollments'  => $totalEnrollments,
                'total_revenue'      => number_format($totalRevenue, 0, ',', '.') . ' VNĐ',
                'recent_enrollments' => $recentEnrollments
            ]
        ]);
    }

    /**
     * Trang Dashboard Admin (Web)
     */
    public function indexWeb()
    {
        $response = $this->dashboard()->getData();
        $stats = $response->data;
        return view('dashboard', compact('stats'));
    }

    /**
     * Trang Quản lý danh sách khóa học
     */
    public function coursesWeb()
    {
        $courses = Course::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Trang Quản lý bài học của một khóa
     */
    public function lessonsWeb(Course $course)
    {
        return view('admin.lessons.index', compact('course'));
    }

    /**
     * Trang xem nội dung bài học (Phòng học)
     */
    public function learningView(Course $course, ?Lesson $lesson = null)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Lấy thông tin bài học
        if (!$lesson) {
            $lesson = $course->lessons()->orderBy('order', 'asc')->first();
        }
        $lessons = $course->lessons()->orderBy('order', 'asc')->get();

        // 2. LOGIC TÍNH TIẾN ĐỘ:
        $totalLessons = $lessons->count();
        $completedCount = $user->completedLessons()
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->count();
        $progress = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;

        return view('learning.index', compact('course', 'lesson', 'lessons', 'progress', 'completedCount', 'totalLessons'));
    }

    /**
     * Trang chủ danh sách khóa học
     */
    public function catalogView()
    {
        $courses = Course::where('status', 1)->get();
        return view('welcome', compact('courses'));
    }

    /**
     * Xử lý Đăng ký khóa học
     */
    public function enroll(Course $course)
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user */ // <-- Fix lỗi Undefined enrolledCourses
            $user = Auth::user();

            $user->enrolledCourses()->syncWithoutDetaching([$course->id]);

            return redirect()->route('learning.view', $course->id)
                ->with('message', 'Chào mừng bạn đến với khóa học!');
        }

        return redirect()->route('login')->with('error', 'Bạn cần đăng nhập.');
    }

    /**
     * Trang chi tiết khóa học (Landing Page)
     */
    public function courseDetail(Course $course)
    {
        $lessons = $course->lessons()->orderBy('order', 'asc')->get();
        return view('courses.show', compact('course', 'lessons'));
    }

    /**
     * Trang danh sách khóa học của học viên
     */
    public function myCourses()
    {
        if (!Auth::check()) return redirect()->route('login');

        /** @var \App\Models\User $user */ // <-- Fix lỗi Undefined enrolledCourses & completedLessons
        $user = Auth::user();

        $courses = $user->enrolledCourses()->with('lessons')->get();

        foreach ($courses as $course) {
            $totalLessons = $course->lessons->count();

            // Lấy số bài đã học của user này trong khóa này
            $completedCount = $user->completedLessons()
                ->whereIn('lesson_id', $course->lessons->pluck('id'))
                ->count();

            $course->progress = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;
        }

        return view('my-courses', compact('courses'));
    }
}
