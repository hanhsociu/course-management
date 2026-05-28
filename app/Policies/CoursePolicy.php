<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use App\Services\CourseAccessService;

class CoursePolicy
{
    public function __construct(
        protected CourseAccessService $access
    ) {}

    public function view(?User $user, Course $course): bool
    {
        return (bool) $course->status;
    }

    public function learn(User $user, Course $course): bool
    {
        return $this->access->canLearn($user, $course);
    }

    public function manage(User $user, Course $course): bool
    {
        return $this->access->canManage($user, $course);
    }

    public function enroll(User $user, Course $course): bool
    {
        if ($this->access->canManage($user, $course)) {
            return false;
        }

        return ! $this->access->isEnrolled($user, $course);
    }
}
