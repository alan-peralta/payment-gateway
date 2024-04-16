<?php

namespace App\Modules\Transaction\Providers;

use App\Modules\Transaction\Interfaces\TransactionServiceInterface;
use App\Modules\Transaction\Services\TransactionService;
use Illuminate\Support\ServiceProvider;

class TransactionServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(TransactionServiceInterface::class, fn () => $this->app->make(TransactionService::class));

    }

}
