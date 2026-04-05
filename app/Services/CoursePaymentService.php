<?php

namespace App\Services;

use App\Models\CoursePayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CoursePaymentService
{
    /**
     * Đánh dấu đã thanh toán và ghi nhận đăng ký khóa (idempotent).
     */
    public function markPaidAndEnroll(CoursePayment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $payment = CoursePayment::whereKey($payment->id)->lockForUpdate()->first();
            if (!$payment || $payment->status === 'paid') {
                return;
            }

            $payment->user->enrolledCourses()->syncWithoutDetaching([$payment->course_id]);

            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        });
    }

    public function logWebhookFailure(\Throwable $e): void
    {
        Log::warning('PayOS webhook: '.$e->getMessage(), ['exception' => $e]);
    }
}
