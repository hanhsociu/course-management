<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;

class EnrollmentProgressService
{
    public function syncForUserCourse(int $userId, int $courseId): void
    {
        $total = Lesson::query()
            ->where('course_id', $courseId)
            ->where('status', true)
            ->count();

        if ($total === 0) {
            return;
        }

        $done = LessonProgress::query()
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('is_completed', true)
            ->count();

        $percent = (int) round(($done / $total) * 100);
        $completed = $percent >= 100;

        Enrollment::query()
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->update([
                'progress_percent' => $percent,
                'status' => $completed ? Enrollment::STATUS_COMPLETED : Enrollment::STATUS_ACTIVE,
                'completed_at' => $completed ? now() : null,
            ]);
    }

    public function markLessonComplete(int $userId, Lesson $lesson, bool $completed = true): LessonProgress
    {
        $progress = LessonProgress::query()->updateOrCreate(
            [
                'user_id' => $userId,
                'lesson_id' => $lesson->id,
            ],
            [
                'course_id' => $lesson->course_id,
                'is_completed' => $completed,
                'completed_at' => $completed ? now() : null,
            ]
        );

        $this->syncForUserCourse($userId, $lesson->course_id);

        return $progress;
    }

    public function toggleLessonComplete(int $userId, Lesson $lesson): LessonProgress
    {
        $existing = LessonProgress::query()
            ->where('user_id', $userId)
            ->where('lesson_id', $lesson->id)
            ->first();

        $newState = ! ($existing?->is_completed ?? false);

        return $this->markLessonComplete($userId, $lesson, $newState);
    }
}
