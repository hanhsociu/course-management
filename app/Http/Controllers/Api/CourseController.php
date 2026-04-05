<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:1|max:50',
        ]);

        $perPage = min((int) $request->input('per_page', 10), 50);

        $courses = Course::query()
            ->published()
            ->search($request->input('q'))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách khóa học thành công',
            'data' => $courses
        ]);
    }

    // ... code hàm index cũ của bạn ...

    // Hàm Tạo khóa học mới (Chỉ Admin)
    public function store(Request $request)
    {
        // 1. Kiểm tra dữ liệu đầu vào
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'status' => 'boolean'
        ]);

        // 2. Lưu vào database
        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'status' => $request->status ?? 1 // Mặc định là Active (1) nếu không truyền
        ]);

        // 3. Trả về kết quả
        return response()->json([
            'success' => true,
            'message' => 'Tạo khóa học mới thành công!',
            'data' => $course
        ], 201);
    }

    // Cập nhật thông tin khóa học (Chỉ Admin)
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'price' => 'numeric|min:0',
            'status' => 'boolean'
        ]);

        $course->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật khóa học thành công!',
            'data' => $course
        ]);
    }

    // Xóa khóa học (Chỉ Admin)
    public function destroy(Course $course)
    {
        // Vì bạn dùng SoftDeletes nên nó sẽ tự động chuyển vào "thùng rác"
        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa khóa học thành công (Xóa mềm)!'
        ]);
    }
}

