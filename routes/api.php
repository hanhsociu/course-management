<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\ProgressController;

// ==========================================
// 1. PUBLIC ROUTES (Không cần đăng nhập)
// ==========================================

// API Danh sách khóa học
Route::get('/courses', [CourseController::class, 'index']);

// API Login lấy Token
Route::post('/login-token', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Sai tài khoản hoặc mật khẩu'], 401);
    }

    $token = $user->createToken('API Token')->plainTextToken;
    return response()->json(['token' => $token]);
});

// API Đăng ký lấy Token
Route::post('/register-api', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'user'
    ]);

    $token = $user->createToken('API Token')->plainTextToken;

    return response()->json([
        'message' => 'Đăng ký thành công!',
        'user' => $user,
        'token' => $token
    ], 201);
});

// ==========================================
// 2. PROTECTED ROUTES (Bắt buộc có Token)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {

    // API Đăng ký khóa học [cite: 76, 101]
    Route::post('/enroll', [EnrollmentController::class, 'store']);

    // API Lấy danh sách bài học (Kèm middleware chặn học chui check.enroll) [cite: 49, 102]
    Route::get('/courses/{course}/lessons', [LessonController::class, 'index'])
        ->middleware('check.enroll');

    // API Lưu tiến độ học tập [cite: 78, 105]
    Route::post('/progress', [ProgressController::class, 'store']);
});
