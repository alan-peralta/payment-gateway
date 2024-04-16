<?php

namespace App\Modules\Wallet\Providers;

use App\Modules\Wallet\Interfaces\DepositWalletServiceInterface;
use App\Modules\Wallet\Interfaces\WithdrawalWalletServiceInterface;
use App\Modules\Wallet\Services\DepositWalletService;
use App\Modules\Wallet\Services\WithdrawalWalletService;
use Illuminate\Support\ServiceProvider;

class WalletServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(WithdrawalWalletServiceInterface::class, fn () => $this->app->make(WithdrawalWalletService::class));
        $this->app->bind(DepositWalletServiceInterface::class, fn () => $this->app->make(DepositWalletService::class));
    }
}
