<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // BẮT BUỘC PHẢI CÓ DÒNG NÀY

class User extends Authenticatable
{
    // BẮT BUỘC PHẢI THÊM HasApiTokens VÀO ĐÂY
    use HasApiTokens, HasFactory, Notifiable;

    // Thêm trường role để sau này phân quyền
    protected $fillable = ['name', 'email', 'password', 'role'];

    // User có nhiều lượt đăng ký khóa học
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Helper method tiện lợi để check admin sau này
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Một người dùng có thể đăng ký nhiều khóa học (Many-to-Many)
    // Một người dùng có thể đăng ký nhiều khóa học (Many-to-Many)
    public function enrolledCourses()
    {
        // Đổi 'course_user' thành 'enrollments' cho đồng bộ
        return $this->belongsToMany(Course::class, 'enrollments')->withTimestamps();
    }

    public function completedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user')->withTimestamps();
    }
}
