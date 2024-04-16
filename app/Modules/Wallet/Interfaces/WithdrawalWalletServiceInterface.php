<?php

namespace App\Modules\Wallet\Interfaces;

interface WithdrawalWalletServiceInterface
{
    public function execute(string $walletID, int $amount);
}
