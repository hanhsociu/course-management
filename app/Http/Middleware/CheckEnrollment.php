<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Enrollment;

class CheckEnrollment
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Lấy tham số course từ URL
        $courseParam = $request->route('course');

        // SỬA Ở ĐÂY: Kiểm tra xem nó là Object (do Laravel bind) hay chỉ là ID thường, để lấy ra đúng con số.
        $courseId = is_object($courseParam) ? $courseParam->id : $courseParam;

        $isEnrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->exists();

        if (!$isEnrolled) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn chưa đăng ký khóa học này nên không thể xem nội dung bài học!'
            ], 403);
        }

        return $next($request);
    }
}
