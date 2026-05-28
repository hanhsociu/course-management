<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->where('role', 'user')->update(['role' => 'student']);
        DB::table('users')->whereNull('role')->update(['role' => 'student']);

        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('password');
            $table->string('phone', 20)->nullable()->after('avatar');
            $table->boolean('status')->default(true)->after('phone');
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'instructor', 'student') NOT NULL DEFAULT 'student'");
        }
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(255) NOT NULL DEFAULT 'user'");

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'phone', 'status']);
        });

        DB::table('users')->where('role', 'student')->update(['role' => 'user']);
    }
};
