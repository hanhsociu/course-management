<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate dữ liệu đầu vào
        $request->validate([
            'course_id' => 'required|exists:courses,id'
        ]);

        $user = $request->user(); // Lấy thông tin user đang đăng nhập
        $courseId = $request->course_id;

        // 2. Kiểm tra user đã đăng ký khóa này chưa
        $isEnrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->exists();

        if ($isEnrolled) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã đăng ký khóa học này rồi!'
            ], 400);
        }

        // 3. Xử lý lưu database an toàn với Transaction
        try {
            DB::transaction(function () use ($user, $courseId) {
                Enrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $courseId,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Đăng ký khóa học thành công!'
            ], 201);
        } catch (\Exception $e) {
            // Nếu có lỗi (ví dụ chết DB giữa chừng), transaction sẽ tự rollback
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau.'
            ], 500);
        }
    }
}
