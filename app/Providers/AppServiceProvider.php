<?php

namespace App\Providers;

use App\Models\Course;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\PayOS\PayOS::class, function () {
            $cfg = config('services.payos', []);

            $clientId = $cfg['client_id'] ?? null;
            $apiKey = $cfg['api_key'] ?? null;
            $checksumKey = $cfg['checksum_key'] ?? null;

            if (!is_string($clientId) || $clientId === ''
                || !is_string($apiKey) || $apiKey === ''
                || !is_string($checksumKey) || $checksumKey === '') {
                throw new \RuntimeException(
                    'PayOS: thiếu PAYOS_CLIENT_ID / PAYOS_API_KEY / PAYOS_CHECKSUM_KEY (kiểm tra .env và chạy php artisan config:clear).'
                );
            }

            return new \PayOS\PayOS(
                clientId: $clientId,
                apiKey: $apiKey,
                checksumKey: $checksumKey,
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFour();

        Route::bind('course', function (string $value) {
            return Course::query()
                ->where(function ($query) use ($value) {
                    $query->where('slug', $value);
                    if (ctype_digit($value)) {
                        $query->orWhere('id', $value);
                    }
                })
                ->firstOrFail();
        });

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
