<?php

namespace App\Modules\Payments\Services\Xpto;

use App\Modules\Payments\Interfaces\PaymentAuthorizationServiceInterface;
use App\Modules\Transaction\Models\Transaction;
use Illuminate\Support\Facades\Http;

class PaymentAuthorizationService implements PaymentAuthorizationServiceInterface
{
    public function execute(Transaction $transaction): bool
    {
        $baseUrl = config('payments.providers.xpto.base_url');
        $response = Http::get($baseUrl);

        return filter_var($response->json('message') == 'Autorizado', FILTER_VALIDATE_BOOLEAN);
    }
}
