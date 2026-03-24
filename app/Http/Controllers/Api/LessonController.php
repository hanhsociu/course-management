<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    // Sử dụng Route Model Binding của Laravel để tự động tìm Course theo ID
    public function index(Course $course)
    {
        // Lấy danh sách bài học, sắp xếp theo thứ tự (order)
        $lessons = $course->lessons()->orderBy('order')->get();

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách bài học thành công!',
            'data' => $lessons
        ]);
    }
}
