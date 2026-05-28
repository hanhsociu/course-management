<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Services\EnrollmentProgressService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, EnrollmentProgressService $progressService)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
        ]);

        $user = $request->user();
        $lesson = Lesson::with('course')->findOrFail($request->lesson_id);

        $this->authorize('learn', $lesson->course);

        $progress = $progressService->markLessonComplete($user->id, $lesson, true);

        $courseId = $lesson->course_id;
        $totalLessons = $lesson->course->lessons()->where('status', true)->count();
        $learnedLessons = $user->lessonProgresses()
            ->where('course_id', $courseId)
            ->where('is_completed', true)
            ->count();

        $completionPercentage = $totalLessons > 0
            ? (int) round(($learnedLessons / $totalLessons) * 100)
            : 0;

        return response()->json([
            'success' => true,
            'message' => 'Đã đánh dấu hoàn thành bài học!',
            'data' => [
                'course_id' => $courseId,
                'progress_saved' => $progress,
                'stats' => [
                    'total_lessons' => $totalLessons,
                    'learned_lessons' => $learnedLessons,
                    'completion_percentage' => $completionPercentage.'%',
                ],
            ],
        ], 201);
    }
}
