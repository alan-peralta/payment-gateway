<?php

namespace App\Modules\Payments\Providers;

use App\Modules\Payments\Exceptions\PaymentException;
use App\Modules\Payments\Interfaces\PaymentAuthorizationServiceInterface;
use App\Modules\Payments\Services\Xpto\PaymentAuthorizationService;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(PaymentAuthorizationServiceInterface::class, function () {
            if (config('payments.default') === 'xpto') {
                return new PaymentAuthorizationService();
            }

            throw PaymentException::methodNotImplemented();
        });
    }
}
