<?php

use App\Http\Controllers\Admin\EnrollmentManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\PayOSPaymentController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// =========================================================
// 1. VÙNG CÔNG CỘNG (GUEST/PUBLIC)
// =========================================================
Route::get('/', [AdminController::class, 'catalogView'])->name('catalog');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/courses/{course}/preview/{lesson?}', [CourseController::class, 'preview'])
    ->name('courses.preview');

Route::post('/payment/payos/webhook', [PayOSPaymentController::class, 'webhook'])
    ->withoutMiddleware([ValidateCsrfToken::class]);
Route::get('/payment/payos/return', [PayOSPaymentController::class, 'return'])->name('payment.payos.return');
Route::get('/payment/payos/cancel', [PayOSPaymentController::class, 'cancel'])->name('payment.payos.cancel');
Route::get('/payment/success', [PayOSPaymentController::class, 'return'])->name('payment.payos.success');
Route::get('/payment/cancel', [PayOSPaymentController::class, 'cancel'])->name('payment.payos.cancel_legacy');

// =========================================================
// 2. VÙNG PHẢI ĐĂNG NHẬP
// =========================================================
Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('catalog');
})->middleware('auth')->name('logout');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'indexWeb'])->name('dashboard');

    Route::get('/my-courses', [AdminController::class, 'myCourses'])->name('my-courses');

    Route::get('/my-courses/{course}/learn/{lesson?}', [LearningController::class, 'learn'])
        ->middleware('course.learn')
        ->name('courses.learn');

    // Redirect route cũ
    Route::get('/learning/{course}/{lesson?}', function (\App\Models\Course $course, ?\App\Models\Lesson $lesson = null) {
        return redirect()->route('courses.learn', array_filter([
            'course' => $course,
            'lesson' => $lesson?->id,
        ]));
    })->name('learning.view');

    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('courses.enroll');
    Route::post('/courses/{course}/checkout', [PayOSPaymentController::class, 'checkout'])->name('courses.checkout');

    Route::view('profile', 'profile')->name('profile');
});

// =========================================================
// 3. VÙNG ADMIN
// =========================================================
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/courses', [AdminController::class, 'coursesWeb'])->name('admin.courses');
    Route::get('/admin/courses/{course}/lessons', [AdminController::class, 'lessonsWeb'])->name('admin.courses.lessons');
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users');
    Route::get('/admin/enrollments', [EnrollmentManagementController::class, 'index'])->name('admin.enrollments');
});

require __DIR__.'/auth.php';
