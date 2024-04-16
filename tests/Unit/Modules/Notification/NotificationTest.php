<?php

namespace Modules\Notification;

use App\Modules\Notification\Events\PaymentProcessed;
use App\Modules\Notification\Exceptions\NotificationException;
use App\Modules\Transaction\DTO\TransactionDTO;
use Illuminate\Support\Facades\Config;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    public function testNotification()
    {
        Config::set('notifications.default', 'ypto');
        $transactionDTO = new TransactionDTO(1000, Uuid::uuid4(), Uuid::uuid4());

        $this->expectException(NotificationException::class);
        $this->expectExceptionMessage(NotificationException::methodNotImplemented()->getMessage());

        PaymentProcessed::dispatch($transactionDTO);
    }
}
