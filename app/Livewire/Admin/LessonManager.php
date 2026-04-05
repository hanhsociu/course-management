<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Lesson;
use App\Models\Course;

class LessonManager extends Component
{
    public $course; // Khóa học hiện tại
    public $lessonId, $title, $content, $order = 1;

    public $is_preview = false;

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
        $this->reset(['title', 'content', 'order', 'lessonId', 'is_preview']);
        $nextOrder = (int) ($this->course->lessons()->max('order') ?? 0) + 1;
        $this->order = $nextOrder;
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $lesson = Lesson::where('course_id', $this->course->id)->findOrFail($id);
        $this->lessonId = $lesson->id;
        $this->title = $lesson->title;
        $this->content = $lesson->content;
        $this->order = $lesson->order;
        $this->is_preview = (bool) $lesson->is_preview;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        Lesson::where('course_id', $this->course->id)->findOrFail($id)->delete();
        session()->flash('message', 'Đã xóa bài học.');
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|min:3',
            'content' => 'required',
            'order' => 'required|numeric',
        ]);

        $payload = [
            'title' => $this->title,
            'content' => $this->content,
            'order' => $this->order,
            'is_preview' => (bool) $this->is_preview,
        ];

        if ($this->lessonId) {
            $lesson = Lesson::where('course_id', $this->course->id)->findOrFail($this->lessonId);
            $lesson->update($payload);
            session()->flash('message', 'Cập nhật bài học thành công!');
        } else {
            Lesson::create(array_merge($payload, ['course_id' => $this->course->id]));
            session()->flash('message', 'Thêm bài học mới thành công!');
        }

        $this->isModalOpen = false;
        $this->reset(['title', 'content', 'order', 'lessonId', 'is_preview']);
    }
}
