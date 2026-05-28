<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('video_url', 500)->nullable()->after('content');
            $table->integer('video_duration')->nullable()->after('video_url'); // tính bằng giây
            $table->enum('video_type', ['upload', 'youtube', 'vimeo'])
                ->default('upload')
                ->after('video_duration');
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['video_url', 'video_duration', 'video_type']);
        });
    }
};
