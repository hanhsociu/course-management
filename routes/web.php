<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EnrollmentManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\PayOSPaymentController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

// =========================================================
// 1. VÙNG CÔNG CỘNG (GUEST/PUBLIC) - AI CŨNG XEM ĐƯỢC
// =========================================================
Route::get('/', [AdminController::class, 'catalogView'])->name('catalog');
Route::get('/courses/{course}', [AdminController::class, 'courseDetail'])->name('courses.show');
Route::get('/courses/{course}/preview/{lesson?}', [AdminController::class, 'previewLearningView'])
    ->name('courses.preview');

Route::post('/payment/payos/webhook', [PayOSPaymentController::class, 'webhook'])
    ->withoutMiddleware([ValidateCsrfToken::class]);


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

    // Nút đăng ký khóa học (miễn phí) / thanh toán PayOS
    Route::post('/courses/{course}/enroll', [AdminController::class, 'enroll'])->name('courses.enroll');
    Route::post('/courses/{course}/checkout', [PayOSPaymentController::class, 'checkout'])->name('courses.checkout');
    Route::get('/payment/payos/return', [PayOSPaymentController::class, 'return'])->name('payment.payos.return');
    Route::get('/payment/payos/cancel', [PayOSPaymentController::class, 'cancel'])->name('payment.payos.cancel');
    // Trùng với PAYOS_RETURN_URL / PAYOS_CANCEL_URL trong .env (project khác của bạn)
    Route::get('/payment/success', [PayOSPaymentController::class, 'return'])->name('payment.payos.success');
    Route::get('/payment/cancel', [PayOSPaymentController::class, 'cancel'])->name('payment.payos.cancel_legacy');

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

    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users');
    Route::get('/admin/enrollments', [EnrollmentManagementController::class, 'index'])->name('admin.enrollments');
});

require __DIR__ . '/auth.php';
