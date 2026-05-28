<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->foreignId('section_id')->nullable()->after('course_id')->constrained('course_sections')->nullOnDelete();
            $table->string('video_storage')->nullable()->after('video_url');
            $table->unsignedInteger('duration_seconds')->default(0)->after('video_type');
            $table->string('attachment_url', 500)->nullable()->after('duration_seconds');
            $table->boolean('status')->default(true)->after('is_preview');
        });

        if (Schema::hasColumn('lessons', 'video_duration')) {
            DB::table('lessons')
                ->whereNotNull('video_duration')
                ->update(['duration_seconds' => DB::raw('video_duration')]);

            Schema::table('lessons', function (Blueprint $table) {
                $table->dropColumn('video_duration');
            });
        }

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE lessons MODIFY COLUMN video_type ENUM('upload', 'youtube', 'vimeo', 'external') NOT NULL DEFAULT 'upload'");
        }

        Schema::table('lessons', function (Blueprint $table) {
            $table->index(['course_id', 'section_id', 'order']);
            $table->index(['course_id', 'status']);
            $table->index('section_id');
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropIndex(['course_id', 'section_id', 'order']);
            $table->dropIndex(['course_id', 'status']);
            $table->dropIndex(['section_id']);
            $table->dropColumn(['section_id', 'video_storage', 'duration_seconds', 'attachment_url', 'status']);
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->integer('video_duration')->nullable()->after('video_url');
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE lessons MODIFY COLUMN video_type ENUM('upload', 'youtube', 'vimeo') NOT NULL DEFAULT 'upload'");
        }
    }
};
