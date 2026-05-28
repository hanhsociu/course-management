<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('lesson_progresses')) {
            return;
        }

        $this->migrateFromProgresses();
        $this->migrateFromLessonUser();
        $this->migrateFromCourseUser();
    }

    public function down(): void
    {
        // Dữ liệu legacy không khôi phục tự động.
    }

    private function migrateFromProgresses(): void
    {
        if (! Schema::hasTable('progresses')) {
            return;
        }

        DB::table('progresses')->orderBy('id')->chunk(200, function ($rows) {
            foreach ($rows as $row) {
                $this->upsertLessonProgress(
                    (int) $row->user_id,
                    (int) $row->lesson_id,
                    true,
                    0,
                    $row->completed_at,
                    $row->created_at,
                    $row->updated_at,
                );
            }
        });
    }

    private function migrateFromLessonUser(): void
    {
        if (! Schema::hasTable('lesson_user')) {
            return;
        }

        DB::table('lesson_user')->orderBy('id')->chunk(200, function ($rows) {
            foreach ($rows as $row) {
                $this->upsertLessonProgress(
                    (int) $row->user_id,
                    (int) $row->lesson_id,
                    true,
                    0,
                    $row->created_at,
                    $row->created_at,
                    $row->updated_at,
                );
            }
        });
    }

    private function migrateFromCourseUser(): void
    {
        if (! Schema::hasTable('course_user') || ! Schema::hasTable('enrollments')) {
            return;
        }

        DB::table('course_user')->orderBy('id')->chunk(200, function ($rows) {
            foreach ($rows as $row) {
                DB::table('enrollments')->updateOrInsert(
                    [
                        'user_id' => $row->user_id,
                        'course_id' => $row->course_id,
                    ],
                    [
                        'status' => 'active',
                        'progress_percent' => 0,
                        'enrolled_at' => $row->created_at ?? now(),
                        'created_at' => $row->created_at ?? now(),
                        'updated_at' => $row->updated_at ?? now(),
                    ]
                );
            }
        });
    }

    private function upsertLessonProgress(
        int $userId,
        int $lessonId,
        bool $isCompleted,
        int $watchSeconds,
        mixed $completedAt,
        mixed $createdAt,
        mixed $updatedAt,
    ): void {
        $lesson = DB::table('lessons')->where('id', $lessonId)->first();
        if (! $lesson) {
            return;
        }

        $existing = DB::table('lesson_progresses')
            ->where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->first();

        if ($existing) {
            DB::table('lesson_progresses')->where('id', $existing->id)->update([
                'is_completed' => $existing->is_completed || $isCompleted,
                'watch_seconds' => max((int) $existing->watch_seconds, $watchSeconds),
                'completed_at' => $existing->completed_at ?? $completedAt,
                'updated_at' => $updatedAt ?? now(),
            ]);

            return;
        }

        DB::table('lesson_progresses')->insert([
            'user_id' => $userId,
            'course_id' => $lesson->course_id,
            'lesson_id' => $lessonId,
            'is_completed' => $isCompleted,
            'watch_seconds' => $watchSeconds,
            'completed_at' => $completedAt,
            'created_at' => $createdAt ?? now(),
            'updated_at' => $updatedAt ?? now(),
        ]);
    }
};
