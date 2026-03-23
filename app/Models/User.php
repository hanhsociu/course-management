<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// app/Models/User.php
class User extends Authenticatable
{
    // Thêm trường role để sau này phân quyền [cite: 91, 92]
    protected $fillable = ['name', 'email', 'password', 'role'];

    // User có nhiều lượt đăng ký khóa học [cite: 17, 18]
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Helper method tiện lợi để check admin sau này
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
