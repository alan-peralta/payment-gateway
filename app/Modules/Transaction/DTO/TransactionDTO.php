<?php

namespace App\Modules\Transaction\DTO;

readonly class TransactionDTO
{
    public function __construct(
        public int $amount,
        public string $payer,
        public string $payee
    )
    {
    }
}
