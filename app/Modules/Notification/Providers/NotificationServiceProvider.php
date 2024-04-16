<?php

namespace App\Modules\Notification\Providers;

use App\Modules\Notification\Exceptions\NotificationException;
use App\Modules\Notification\Interfaces\NotificationServiceInterfaces;
use App\Modules\Notification\Services\Xpto\NotificationService;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->app->bind(NotificationServiceInterfaces::class, function () {
            if (config('notifications.default') == 'xpto') {
                return new NotificationService();
            }

            throw NotificationException::methodNotImplemented();
        });
    }
}
