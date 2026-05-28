<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Services\CourseAccessService;
use App\Services\LessonVideoService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LearningController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected CourseAccessService $access,
        protected LessonVideoService $videoService,
    ) {}

    public function learn(Course $course, ?Lesson $lesson = null)
    {
        $this->authorize('learn', $course);

        $user = auth()->user();

        $lessons = $course->lessons()
            ->where('status', true)
            ->orderBy('order')
            ->with(['attachments', 'section'])
            ->get();

        if ($lessons->isEmpty()) {
            return redirect()->route('courses.show', $course)
                ->with('info', 'Khóa học chưa có bài học.');
        }

        if ($lesson === null) {
            $lesson = $lessons->first();
        } else {
            if ((int) $lesson->course_id !== (int) $course->id || ! $lesson->status) {
                abort(404);
            }
        }

        $lesson->load('attachments');

        $completedLessonIds = LessonProgress::query()
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('is_completed', true)
            ->pluck('lesson_id');

        $enrollment = $this->access->enrollmentFor($user, $course);
        $progress = $enrollment?->progress_percent ?? 0;
        $completedCount = $completedLessonIds->count();
        $totalLessons = $lessons->count();

        $course->load([
            'sections' => fn ($q) => $q->where('status', true)->orderBy('order'),
            'sections.lessons' => fn ($q) => $q->where('status', true)->orderBy('order'),
        ]);

        return view('learning.learn', [
            'course' => $course,
            'lesson' => $lesson,
            'lessons' => $lessons,
            'completedLessonIds' => $completedLessonIds,
            'progress' => $progress,
            'completedCount' => $completedCount,
            'totalLessons' => $totalLessons,
            'videoService' => $this->videoService,
        ]);
    }
}
