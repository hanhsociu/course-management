<?php

namespace App\Livewire\Learning;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class MarkComplete extends Component
{
    public $lessonId;

    public function toggleComplete()
    {
        /** @var User $user */ // <-- Dòng "thần thánh" này sẽ xóa sổ lỗi Undefined
        $user = Auth::user();

        if ($user) {
            $user->completedLessons()->toggle($this->lessonId);
            $this->dispatch('progress-updated');
        }
    }

    public function render()
    {
        /** @var User $user */
        $user = Auth::user();

        // Kiểm tra xem bài học này đã được user hoàn thành chưa
        $isCompleted = $user ? $user->completedLessons()->where('lesson_id', $this->lessonId)->exists() : false;

        return view('livewire.learning.mark-complete', compact('isCompleted'));
    }
}
