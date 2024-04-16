<?php

namespace App\Modules\Notification\Listeners;

use App\Modules\Notification\Events\PaymentProcessed;
use App\Modules\Notification\Interfaces\NotificationServiceInterfaces;

class SendPaymentNotification
{
    public NotificationServiceInterfaces $notificationService;

    public function __construct(NotificationServiceInterfaces $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(PaymentProcessed $event): void
    {
        $this->notificationService->execute();
    }
}
