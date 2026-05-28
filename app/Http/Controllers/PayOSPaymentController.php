<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursePayment;
use App\Models\User;
use App\Services\CoursePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PayOS\Exceptions\APIException;
use PayOS\Exceptions\WebhookException;
use PayOS\Models\V2\PaymentRequests\CreatePaymentLinkRequest;
use PayOS\Models\V2\PaymentRequests\PaymentLinkItem;
use PayOS\Models\V2\PaymentRequests\PaymentLinkStatus;

class PayOSPaymentController extends Controller
{
    public function __construct(
        private CoursePaymentService $coursePaymentService
    ) {}

    public function checkout(Request $request, Course $course)
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            abort(403);
        }

        if ($user->isAdmin()) {
            $user->enrolledCourses()->syncWithoutDetaching([
                $course->id => [
                    'status' => \App\Models\Enrollment::STATUS_ACTIVE,
                    'progress_percent' => 0,
                    'enrolled_at' => now(),
                ],
            ]);

            return redirect()->route('courses.learn', $course)
                ->with('message', 'Admin: đã mở khóa học.');
        }

        if ($user->enrolledCourses->contains($course->id)) {
            return redirect()->route('courses.learn', $course)
                ->with('info', 'Bạn đã sở hữu khóa học này.');
        }

        $amount = (int) round((float) $course->price);
        if ($amount > 0 && $amount < 2000) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'Số tiền khóa học phải từ 2.000đ trở lên (yêu cầu PayOS). Hãy cập nhật giá khóa trong admin.');
        }

        if ($amount <= 0) {
            $user->enrolledCourses()->syncWithoutDetaching([
                $course->id => [
                    'status' => \App\Models\Enrollment::STATUS_ACTIVE,
                    'progress_percent' => 0,
                    'enrolled_at' => now(),
                ],
            ]);

            return redirect()->route('courses.learn', $course)
                ->with('message', 'Đăng ký khóa miễn phí thành công!');
        }

        if ((int) $course->status !== 1) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'Khóa học không khả dụng.');
        }

        // Đóng các đơn pending cũ của cùng user/khóa để tránh tồn nhiều đơn chờ thanh toán.
        CoursePayment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'pending')
            ->update(['status' => 'cancelled']);

        $payOS = $this->payOSClient();

        $orderCode = $this->generateUniqueOrderCode();

        $payment = CoursePayment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'order_code' => $orderCode,
            'amount' => $amount,
            'currency' => 'VND',
            'status' => 'pending',
        ]);

        $returnUrl = $this->payOSCallbackUrl('return');
        $cancelUrl = $this->payOSCallbackUrl('cancel');

        $titleSafe = str_replace(["\n", "\r", '—', '–'], [' ', ' ', '-', '-'], (string) $course->title);
        // PayOS: mô tả thanh toán tối đa 25 ký tự (lỗi code 20 nếu vượt)
        $description = Str::limit(
            trim($titleSafe) !== '' ? $titleSafe : ('Khoa hoc '.$course->id),
            25,
            ''
        );

        $item = new PaymentLinkItem(
            name: Str::limit($course->title, 120, ''),
            quantity: 1,
            price: $amount
        );

        $createRequest = new CreatePaymentLinkRequest(
            orderCode: $orderCode,
            amount: $amount,
            description: $description,
            cancelUrl: $cancelUrl,
            returnUrl: $returnUrl,
            signature: null,
            items: [$item],
            buyerName: $user->name,
            buyerEmail: $user->email,
        );

        try {
            $result = $payOS->paymentRequests->create($createRequest);
            $checkoutUrl = $result->checkoutUrl ?? null;
            if (!$checkoutUrl) {
                throw new \RuntimeException('PayOS không trả về checkoutUrl.');
            }

            return redirect()->away($checkoutUrl);
        } catch (APIException $e) {
            $payment->delete();
            Log::error('PayOS create payment failed', [
                'course_id' => $course->id,
                'message' => $e->getMessage(),
                'http_status' => $e->status,
                'payos_error' => $e->error,
                'return_url' => $returnUrl,
                'amount' => $amount,
            ]);

            $userMessage = config('app.debug')
                ? ('PayOS: '.$e->getMessage())
                : 'Không tạo được link thanh toán. Vui lòng thử lại sau.';

            return redirect()->route('courses.show', $course)
                ->with('error', $userMessage);
        } catch (\PayOS\Exceptions\PayOSException $e) {
            $payment->delete();
            Log::error('PayOS SDK error', ['course_id' => $course->id, 'message' => $e->getMessage()]);

            return redirect()->route('courses.show', $course)
                ->with('error', config('app.debug') ? $e->getMessage() : 'Lỗi kết nối PayOS. Vui lòng thử lại sau.');
        }
    }

    public function return(Request $request)
    {
        $orderCode = $request->integer('orderCode');
        if (!$orderCode) {
            return redirect()->route('catalog')->with('error', 'Thiếu mã đơn hàng thanh toán.');
        }

        $payment = CoursePayment::where('order_code', $orderCode)->firstOrFail();

        $cancel = filter_var($request->query('cancel'), FILTER_VALIDATE_BOOLEAN);
        $status = $request->query('status');
        $code = $request->query('code');

        if ($cancel || $status === 'CANCELLED') {
            if ($payment->status === 'pending') {
                $payment->update(['status' => 'cancelled']);
            }

            return redirect()->route('courses.show', $payment->course_id)
                ->with('info', 'Bạn đã hủy thanh toán.');
        }

        if ($code === '00' && $status === 'PAID') {
            $this->coursePaymentService->markPaidAndEnroll($payment);

            if (Auth::id() === $payment->user_id) {
                return redirect()->route('courses.learn', $payment->course_id)
                    ->with('message', 'Thanh toán thành công! Bắt đầu học ngay.');
            }

            return redirect()->route('courses.show', $payment->course_id)
                ->with('message', 'Thanh toán thành công. Vui lòng đăng nhập lại để vào phòng học.');
        }

        try {
            $payOS = $this->payOSClient();
            $link = $payOS->paymentRequests->get($orderCode);
            if ($link->status === PaymentLinkStatus::PAID) {
                $this->coursePaymentService->markPaidAndEnroll($payment);

                if (Auth::id() === $payment->user_id) {
                    return redirect()->route('courses.learn', $payment->course_id)
                        ->with('message', 'Thanh toán thành công! Bắt đầu học ngay.');
                }

                return redirect()->route('courses.show', $payment->course_id)
                    ->with('message', 'Thanh toán thành công. Vui lòng đăng nhập lại để vào phòng học.');
            }
        } catch (APIException $e) {
            Log::notice('PayOS return: chưa xác nhận PAID: '.$e->getMessage());
        }

        return redirect()->route('courses.show', $payment->course_id)
            ->with('error', 'Chưa nhận được xác nhận thanh toán. Nếu bạn đã thanh toán, vui lòng đợi vài phút hoặc tải lại trang khóa học.');
    }

    public function cancel(Request $request)
    {
        $orderCode = $request->integer('orderCode');
        if ($orderCode) {
            $payment = CoursePayment::where('order_code', $orderCode)->first();
            if ($payment) {
                if ($payment->status === 'pending') {
                    $payment->update(['status' => 'cancelled']);
                }

                return redirect()->route('courses.show', $payment->course_id)
                    ->with('info', 'Bạn đã hủy thanh toán.');
            }
        }

        return redirect()->route('catalog')->with('info', 'Đã hủy thanh toán.');
    }

    public function webhook(Request $request)
    {
        try {
            $payOS = $this->payOSClient();
            $payload = $request->all();
            $verified = $payOS->webhooks->verify($payload);

            if ($verified->code !== '00') {
                return response('OK', 200);
            }

            $payment = CoursePayment::where('order_code', $verified->orderCode)->first();
            if (!$payment) {
                Log::warning('PayOS webhook: không tìm thấy order_code', ['orderCode' => $verified->orderCode]);

                return response('OK', 200);
            }

            if ((int) $verified->amount !== (int) $payment->amount) {
                Log::warning('PayOS webhook: amount không khớp', [
                    'orderCode' => $verified->orderCode,
                    'expected' => $payment->amount,
                    'got' => $verified->amount,
                ]);

                return response('OK', 200);
            }

            $this->coursePaymentService->markPaidAndEnroll($payment);

            return response('OK', 200);
        } catch (WebhookException $e) {
            $this->coursePaymentService->logWebhookFailure($e);

            return response('Invalid webhook', 400);
        } catch (\Throwable $e) {
            $this->coursePaymentService->logWebhookFailure($e);

            return response('Error', 500);
        }
    }

    private function generateUniqueOrderCode(): int
    {
        do {
            $orderCode = random_int(1_000_000, 2_000_000_000);
        } while (CoursePayment::where('order_code', $orderCode)->exists());

        return $orderCode;
    }

    /**
     * Không type-hint PayOS trên action để tránh ReflectionException khi chưa composer install / autoload lỗi.
     *
     * @return \PayOS\PayOS
     */
    private function payOSClient()
    {
        if (!class_exists(\PayOS\PayOS::class)) {
            abort(503, 'SDK PayOS chưa có. Chạy: composer require payos/payos && composer dump-autoload');
        }

        return app(\PayOS\PayOS::class);
    }

    /**
     * Ưu tiên PAYOS_RETURN_URL / PAYOS_CANCEL_URL trong .env (giống project khác), không có thì dùng route mặc định.
     */
    private function payOSCallbackUrl(string $which): string
    {
        $key = $which === 'cancel' ? 'cancel_url' : 'return_url';
        $fromEnv = config('services.payos.'.$key);

        if (is_string($fromEnv) && trim($fromEnv) !== '') {
            return trim($fromEnv);
        }

        return $which === 'cancel'
            ? route('payment.payos.cancel', [], true)
            : route('payment.payos.return', [], true);
    }
}
