<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id')->constrained('categories')->nullOnDelete();
            $table->foreignId('instructor_id')->nullable()->after('category_id')->constrained('users')->nullOnDelete();
            $table->string('slug')->nullable()->after('title');
            $table->string('short_description', 500)->nullable()->after('description');
            $table->string('thumbnail')->nullable()->after('short_description');
            $table->string('intro_video_url', 500)->nullable()->after('thumbnail');
            $table->decimal('old_price', 10, 2)->nullable()->after('price');
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner')->after('old_price');
            $table->string('language', 50)->default('Vietnamese')->after('level');
            $table->unsignedInteger('duration_minutes')->default(0)->after('language');
            $table->unsignedInteger('total_lessons')->default(0)->after('duration_minutes');
            $table->boolean('is_featured')->default(false)->after('total_lessons');
            $table->json('requirements')->nullable()->after('is_featured');
            $table->json('benefits')->nullable()->after('requirements');
            $table->json('target_students')->nullable()->after('benefits');
            $table->timestamp('published_at')->nullable()->after('status');
        });

        $courses = DB::table('courses')->select('id', 'title', 'status', 'updated_at', 'created_at')->get();
        foreach ($courses as $course) {
            $base = Str::slug($course->title) ?: 'course';
            DB::table('courses')->where('id', $course->id)->update([
                'slug' => $base.'-'.$course->id,
                'published_at' => $course->status ? ($course->updated_at ?? $course->created_at) : null,
            ]);
        }

        Schema::table('courses', function (Blueprint $table) {
            $table->unique('slug');
            $table->index('category_id');
            $table->index('instructor_id');
            $table->index('is_featured');
            $table->index('published_at');
            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['instructor_id']);
            $table->dropUnique(['slug']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['instructor_id']);
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['published_at']);
            $table->dropIndex(['status', 'published_at']);
            $table->dropColumn([
                'category_id',
                'instructor_id',
                'slug',
                'short_description',
                'thumbnail',
                'intro_video_url',
                'old_price',
                'level',
                'language',
                'duration_minutes',
                'total_lessons',
                'is_featured',
                'requirements',
                'benefits',
                'target_students',
                'published_at',
            ]);
        });
    }
};
