<?php

namespace App\Modules\Transaction\Interfaces;

use App\Modules\Transaction\DTO\TransactionDTO;

interface TransactionServiceInterface
{
    public function execute(TransactionDTO $transactionDTO);
}
