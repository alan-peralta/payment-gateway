<?php

namespace App\Modules\Notification\Services\Xpto;

use App\Modules\Notification\Interfaces\NotificationServiceInterfaces;
use Illuminate\Support\Facades\Http;

class NotificationService implements NotificationServiceInterfaces
{

    public function execute(): bool
    {
        $baseUrl = config('notifications.providers.xpto.base_url');

        $response = Http::get($baseUrl);

        return $response->json('message');

    }
}
