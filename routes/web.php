<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminController;

// =========================================================
// 1. VÙNG CÔNG CỘNG (GUEST/PUBLIC) - AI CŨNG XEM ĐƯỢC
// =========================================================
Route::get('/', [AdminController::class, 'catalogView'])->name('catalog');
Route::get('/courses/{course}', [AdminController::class, 'courseDetail'])->name('courses.show');


// =========================================================
// 2. VÙNG PHẢI ĐĂNG NHẬP (USER/STUDENT)
// =========================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard chung
    Route::get('/dashboard', [AdminController::class, 'indexWeb'])->name('dashboard');

    // Trang học tập (Phòng học)
    Route::get('/learning/{course}/{lesson?}', [AdminController::class, 'learningView'])->name('learning.view');

    // Khóa học của tôi
    Route::get('/my-courses', [AdminController::class, 'myCourses'])->name('my-courses');

    // Nút đăng ký khóa học
    Route::post('/courses/{course}/enroll', [AdminController::class, 'enroll'])->name('courses.enroll');

    // Profile cá nhân
    Route::view('profile', 'profile')->name('profile');
});


// =========================================================
// 3. VÙNG ADMIN (CHỈ ADMIN MỚI VÀO ĐƯỢC)
// =========================================================
Route::middleware(['auth', 'admin'])->group(function () {
    // Quản lý khóa học
    Route::get('/admin/courses', [AdminController::class, 'coursesWeb'])->name('admin.courses');

    // Quản lý bài học
    Route::get('/admin/courses/{course}/lessons', [AdminController::class, 'lessonsWeb'])->name('admin.courses.lessons');
});

require __DIR__ . '/auth.php';
