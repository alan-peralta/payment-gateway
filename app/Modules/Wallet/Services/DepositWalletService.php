<?php

namespace App\Modules\Wallet\Services;

use App\Modules\Wallet\Interfaces\DepositWalletServiceInterface;
use App\Modules\Wallet\Models\Wallet;

class DepositWalletService implements DepositWalletServiceInterface
{
    public function execute(string $walletID, int $amount): void
    {
        /** @var Wallet $wallet */
        $wallet = Wallet::query()->find($walletID);
        $wallet->balance += $amount;
        $wallet->save();
    }
}
