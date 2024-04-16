<?php

namespace App\Modules\Payments\Interfaces;

use App\Modules\Transaction\Models\Transaction;

interface PaymentAuthorizationServiceInterface
{
    public function execute(Transaction $transaction): bool;
}
