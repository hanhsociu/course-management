<?php

namespace App\Http\Middleware;

use App\Models\Course;
use App\Services\CourseAccessService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCourseLearnAccess
{
    public function __construct(
        protected CourseAccessService $access
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $course = $request->route('course');
        if (! $course instanceof Course) {
            $course = Course::query()
                ->where('slug', $course)
                ->orWhere('id', $course)
                ->firstOrFail();
        }

        if (! $this->access->canLearn($user, $course)) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'Bạn cần mua hoặc đăng ký khóa học này để vào học.');
        }

        return $next($request);
    }
}
