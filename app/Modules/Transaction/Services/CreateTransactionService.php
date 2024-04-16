<?php

namespace App\Modules\Transaction\Services;

use App\Modules\Transaction\DTO\TransactionDTO;
use App\Modules\Transaction\Enums\TransactionStatusEnums;
use App\Modules\Transaction\Interfaces\CreateTransactionServiceInterface;
use App\Modules\Transaction\Models\Transaction;

class CreateTransactionService implements CreateTransactionServiceInterface
{

    public function execute(TransactionDTO $transactionDTO): Transaction
    {
        $transaction = new Transaction();
        $transaction->origin_wallet_id = $transactionDTO->payer;
        $transaction->destination_wallet_id = $transactionDTO->payee;
        $transaction->amount = $transactionDTO->amount;
        $transaction->status_id = TransactionStatusEnums::PENDING;
        $transaction->save();

        return $transaction;
    }
}
