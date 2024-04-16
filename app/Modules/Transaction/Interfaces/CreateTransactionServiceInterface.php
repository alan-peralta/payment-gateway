<?php

namespace App\Modules\Transaction\Interfaces;

use App\Modules\Transaction\DTO\TransactionDTO;
use App\Modules\Transaction\Models\Transaction;

interface CreateTransactionServiceInterface
{
    public function execute(TransactionDTO $transactionDTO): Transaction;
}
