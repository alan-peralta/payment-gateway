<?php

namespace Modules\Wallet;

use App\Modules\User\Models\User;
use App\Modules\Wallet\Models\Wallet;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateWallet()
    {
        $user = User::factory()->person()->create();

        $wallet = Wallet::factory()
            ->for($user)
            ->create();

        $this->assertDatabaseHas('wallets', [
            'id' => $wallet->id,
            'user_id' => $user->id,
            'balance' => $wallet->balance
        ]);
    }

    public function testCreateWalletWithoutUser()
    {
        $this->expectException(QueryException::class);
        Wallet::factory()->create();
    }
}
