<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    // 1. Lấy danh sách bài học (Dùng chung cho cả User và Admin)
    public function index(Request $request, Course $course)
    {
        $request->validate([
            'per_page' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
        ]);

        $perPage = min((int) $request->input('per_page', 15), 100);

        $lessons = $course->lessons()
            ->orderBy('order')
            ->paginate($perPage)
            ->withQueryString();

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách bài học thành công',
            'data' => $lessons,
        ]);
    }

    // 2. Thêm bài học mới (Chỉ Admin)
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
            'order'     => 'integer',
            'is_preview' => 'boolean',
        ]);

        $lesson = Lesson::create($request->only(['course_id', 'title', 'content', 'order', 'is_preview', 'video_url']));

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
            'order'   => 'integer',
            'is_preview' => 'boolean',
        ]);

        $lesson->update($request->only(['title', 'content', 'order', 'is_preview', 'video_url']));

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
