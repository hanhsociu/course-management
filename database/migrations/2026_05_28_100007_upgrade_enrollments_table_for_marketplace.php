<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->enum('status', ['active', 'completed', 'cancelled', 'expired'])
                ->default('active')
                ->after('course_id');
            $table->unsignedTinyInteger('progress_percent')->default(0)->after('status');
            $table->timestamp('completed_at')->nullable()->after('enrolled_at');
            $table->timestamp('expired_at')->nullable()->after('completed_at');

            $table->index('status');
            $table->index(['user_id', 'status']);
            $table->index(['course_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['course_id', 'status']);
            $table->dropColumn(['status', 'progress_percent', 'completed_at', 'expired_at']);
        });
    }
};
