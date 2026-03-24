<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Course;

class CourseManager extends Component
{
    public $courses;
    public $courseId; // Lưu ID khi sửa bài
    public $title, $description, $price, $status = 1;
    public $isModalOpen = false;

    // Chạy khi component được load hoặc có thay đổi
    public function render()
    {
        $this->courses = Course::orderBy('created_at', 'desc')->get();
        return view('livewire.admin.course-manager');
    }

    // Mở modal để THÊM MỚI
    public function openModal()
    {
        $this->resetFields();
        $this->resetValidation(); // Xóa các thông báo lỗi đỏ cũ (nếu có)
        $this->isModalOpen = true;
    }

    // Mở modal để CHỈNH SỬA
    public function edit($id)
    {
        $this->resetValidation();
        $course = Course::findOrFail($id);

        // Đổ dữ liệu vào các ô input
        $this->courseId = $id;
        $this->title = $course->title;
        $this->description = $course->description;
        $this->price = $course->price;
        $this->status = $course->status;

        $this->isModalOpen = true;
    }

    // Đóng modal
    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->courseId = null; // Quan trọng: Reset ID để lần sau mở ra là "Thêm mới"
    }

    private function resetFields()
    {
        $this->courseId = null;
        $this->title = '';
        $this->description = '';
        $this->price = '';
        $this->status = 1;
    }

    // Hàm "2 trong 1": Vừa Lưu mới, vừa Cập nhật
    public function store()
    {
        $this->validate([
            'title' => 'required|min:5|max:255',
            'price' => 'required|numeric|min:0|max:999999999',
        ]);

        // Logic: Nếu có courseId thì là Update, không có thì là Create
        Course::updateOrCreate(['id' => $this->courseId], [
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'status' => $this->status,
        ]);

        session()->flash('message', $this->courseId ? 'Cập nhật khóa học thành công!' : 'Thêm khóa học mới thành công!');

        $this->closeModal();
    }

    // Xóa khóa học
    public function delete($id)
    {
        Course::find($id)->delete();
        session()->flash('message', 'Đã xóa khóa học vào thùng rác!');
    }
}
