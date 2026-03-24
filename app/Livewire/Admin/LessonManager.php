<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Lesson;
use App\Models\Course;

class LessonManager extends Component
{
    public $course; // Khóa học hiện tại
    public $lessonId, $title, $content, $order = 1;
    public $isModalOpen = false;

    // Nhận course_id từ URL hoặc từ Component cha
    public function mount($courseId)
    {
        $this->course = Course::findOrFail($courseId);
    }

    public function render()
    {
        $lessons = Lesson::where('course_id', $this->course->id)
            ->orderBy('order', 'asc')
            ->get();

        return view('livewire.admin.lesson-manager', [
            'lessons' => $lessons
        ]);
    }

    public function openModal()
    {
        $this->reset(['title', 'content', 'order', 'lessonId']);
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|min:3',
            'content' => 'required',
            'order' => 'required|numeric',
        ]);

        // Lưu vào DB
        \App\Models\Lesson::create([
            'course_id' => $this->course->id, // Lấy ID từ khóa học hiện tại đang xem
            'title' => $this->title,
            'content' => $this->content,
            'order' => $this->order,
        ]);

        session()->flash('message', 'Thêm bài học mới thành công!');
        $this->isModalOpen = false; // Đóng cửa sổ lại
        $this->reset(['title', 'content', 'order']); // Xóa trắng form để lần sau nhập tiếp
    }
}
