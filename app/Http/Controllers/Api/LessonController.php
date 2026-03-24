<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    // 1. Lấy danh sách bài học (Dùng chung cho cả User và Admin)
    public function index(Course $course)
    {
        $lessons = $course->lessons()->orderBy('order')->get();
        return response()->json(['success' => true, 'data' => $lessons]);
    }

    // 2. Thêm bài học mới (Chỉ Admin)
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
            'order'     => 'integer'
        ]);

        $lesson = Lesson::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Thêm bài học mới thành công!',
            'data'    => $lesson
        ], 201);
    }

    // 3. Cập nhật bài học (Chỉ Admin)
    public function update(Request $request, Lesson $lesson)
    {
        $request->validate([
            'title'   => 'string|max:255',
            'content' => 'string',
            'order'   => 'integer'
        ]);

        $lesson->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật bài học thành công!',
            'data'    => $lesson
        ]);
    }

    // 4. Xóa bài học (Chỉ Admin)
    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return response()->json([
            'success' => true,
            'message' => 'Đã xóa bài học thành công!'
        ]);
    }
}
