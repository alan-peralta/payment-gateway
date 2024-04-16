<?php

namespace App\Modules\Notification\Events;

use App\Modules\Transaction\DTO\TransactionDTO;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentProcessed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TransactionDTO $transactionDTO;

    public function __construct(TransactionDTO $transactionDTO)
    {
        $this->transactionDTO = $transactionDTO;
    }
}
