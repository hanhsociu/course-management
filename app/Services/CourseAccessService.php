<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;

class CourseAccessService
{
    public function canManage(User $user, Course $course): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isInstructor() && (int) $course->instructor_id === (int) $user->id;
    }

    public function canLearn(User $user, Course $course): bool
    {
        if ($this->canManage($user, $course)) {
            return true;
        }

        return $this->isEnrolled($user, $course);
    }

    public function isEnrolled(User $user, Course $course): bool
    {
        return Enrollment::query()
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', [Enrollment::STATUS_ACTIVE, Enrollment::STATUS_COMPLETED])
            ->exists();
    }

    public function enrollmentFor(User $user, Course $course): ?Enrollment
    {
        return Enrollment::query()
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
    }
}
