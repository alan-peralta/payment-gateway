<?php

namespace App\Modules\Transaction\Interfaces;

use App\Modules\Transaction\Enums\TransactionStatusEnums;
use App\Modules\Transaction\Models\Transaction;

interface UpdateStatusTransactionServiceInterface
{
    public function execute(Transaction $transaction, TransactionStatusEnums $transactionStatus, string $notes): Transaction;
}
