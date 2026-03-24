<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        // Lấy danh sách khóa học active, mới nhất lên trước, phân trang 10 item/trang
        $courses = Course::where('status', 1)
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách khóa học thành công',
            'data' => $courses
        ]);
    }

    // Các hàm show, store, update, destroy tạm thời để trống, mình sẽ xử lý sau ở phần Admin
}
