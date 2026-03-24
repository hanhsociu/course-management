<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Progress;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate đầu vào
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id'
        ]);

        $user = $request->user();
        $lessonId = $request->lesson_id;

        // Lấy thông tin bài học để biết nó thuộc khóa học nào
        $lesson = Lesson::findOrFail($lessonId);
        $courseId = $lesson->course_id;

        // 2. Task 11: Progress tracking - Lưu tiến độ học tập [cite: 52, 54]
        // Dùng firstOrCreate để user có bấm F5 hay gởi API nhiều lần cũng chỉ tính 1 lần hoàn thành
        $progress = Progress::firstOrCreate([
            'user_id' => $user->id,
            'lesson_id' => $lessonId,
        ]);

        // 3. Task 12: Tính % hoàn thành [cite: 55, 56]
        // Tổng số lesson của khóa học này 
        $totalLessons = Lesson::where('course_id', $courseId)->count();

        // Số lesson user ĐÃ HỌC trong khóa học này 
        $learnedLessons = Progress::where('user_id', $user->id)
            ->whereHas('lesson', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })->count();

        // Tránh lỗi chia cho 0 nếu khóa học chưa có bài nào, sau đó làm tròn số %
        $completionPercentage = $totalLessons > 0
            ? round(($learnedLessons / $totalLessons) * 100)
            : 0;

        // 4. Trả về Response
        return response()->json([
            'success' => true,
            'message' => 'Đã đánh dấu hoàn thành bài học!',
            'data' => [
                'course_id' => $courseId,
                'progress_saved' => $progress,
                'stats' => [
                    'total_lessons' => $totalLessons,
                    'learned_lessons' => $learnedLessons,
                    'completion_percentage' => $completionPercentage . '%'
                ]
            ]
        ], 201);
    }
}
