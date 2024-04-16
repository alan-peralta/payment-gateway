<?php

namespace App\Modules\Transaction\Services;

use App\Modules\Transaction\Enums\TransactionStatusEnums;
use App\Modules\Transaction\Interfaces\UpdateStatusTransactionServiceInterface;
use App\Modules\Transaction\Models\Transaction;

class UpdateStatusTransactionService implements UpdateStatusTransactionServiceInterface
{

    public function execute(Transaction $transaction, TransactionStatusEnums $transactionStatus, string $notes): Transaction
    {
        $transaction->status_id = $transactionStatus->value;
        $transaction->notes = $notes;
        $transaction->save();

        return $transaction;
    }
}
