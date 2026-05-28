<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Services\CourseAccessService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected CourseAccessService $access,
    ) {}

    public function store(Course $course)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để đăng ký.');
        }

        $user = Auth::user();

        if ($this->access->canLearn($user, $course)) {
            return redirect()->route('courses.learn', $course)
                ->with('info', 'Bạn đã có quyền truy cập khóa học này.');
        }

        if ($user->isAdmin() || (float) $course->price <= 0) {
            $user->enrolledCourses()->syncWithoutDetaching([
                $course->id => [
                    'status' => Enrollment::STATUS_ACTIVE,
                    'progress_percent' => 0,
                    'enrolled_at' => now(),
                ],
            ]);

            return redirect()->route('courses.learn', $course)
                ->with('message', 'Đăng ký thành công! Bắt đầu học ngay.');
        }

        return redirect()->route('courses.show', $course)
            ->with('error', 'Khóa học có phí — vui lòng thanh toán bằng PayOS.');
    }
}
