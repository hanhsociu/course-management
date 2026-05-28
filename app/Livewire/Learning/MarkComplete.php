<?php

namespace App\Livewire\Learning;

use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use App\Services\CourseAccessService;
use App\Services\EnrollmentProgressService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MarkComplete extends Component
{
    use AuthorizesRequests;

    public int $lessonId;

    public function toggleComplete(): void
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (! $user) {
            return;
        }

        $lesson = Lesson::with('course')->find($this->lessonId);
        if (! $lesson?->course) {
            return;
        }

        $this->authorize('learn', $lesson->course);

        app(EnrollmentProgressService::class)->toggleLessonComplete($user->id, $lesson);

        $this->dispatch('progress-updated');
    }

    public function render()
    {
        /** @var User|null $user */
        $user = Auth::user();

        $isCompleted = $user
            ? LessonProgress::query()
                ->where('user_id', $user->id)
                ->where('lesson_id', $this->lessonId)
                ->where('is_completed', true)
                ->exists()
            : false;

        return view('livewire.learning.mark-complete', compact('isCompleted'));
    }
}
