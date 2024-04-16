<?php

namespace App\Modules\Wallet\Interfaces;

interface DepositWalletServiceInterface
{
    public function execute(string $walletID, int $amount);
}
