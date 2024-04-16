<?php

return [
    App\Providers\AppServiceProvider::class,
    \App\Modules\Transaction\Providers\TransactionServiceProvider::class,
    \App\Modules\Payments\Providers\PaymentServiceProvider::class,
    \App\Modules\Notification\Providers\NotificationServiceProvider::class,
    \App\Modules\Wallet\Providers\WalletServiceProvider::class,
];
