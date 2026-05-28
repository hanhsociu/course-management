<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Services\CourseAccessService;

class LessonPolicy
{
    public function __construct(
        protected CourseAccessService $access
    ) {}

    public function preview(?User $user, Lesson $lesson, Course $course): bool
    {
        return (int) $lesson->course_id === (int) $course->id
            && $lesson->is_preview
            && $lesson->status;
    }

    public function viewInCourse(User $user, Lesson $lesson, Course $course): bool
    {
        if ((int) $lesson->course_id !== (int) $course->id || ! $lesson->status) {
            return false;
        }

        if ($lesson->is_preview) {
            return true;
        }

        return $this->access->canLearn($user, $course);
    }
}
