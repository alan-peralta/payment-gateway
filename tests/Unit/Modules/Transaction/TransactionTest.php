<?php

namespace Modules\Transaction;

use App\Modules\Transaction\Enums\TransactionStatusEnums;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\User\Models\User;
use App\Modules\Wallet\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreatedTransactionSuccessful()
    {
        $amount = 1000;
        $person = User::factory()->person()->create();
        $company = User::factory()->company()->create();
        $personWallet = Wallet::factory()->for($person)->create();
        $companyWallet = Wallet::factory()->for($company)->create();

        Transaction::query()->create([
            'origin_wallet_id' => $personWallet->id,
            'destination_wallet_id' => $companyWallet->id,
            'amount' => $amount,
            'status_id' => TransactionStatusEnums::PENDING
        ]);

        $this->assertDatabaseHas('transactions', [
            'origin_wallet_id' => $personWallet->id,
            'destination_wallet_id' => $companyWallet->id,
            'amount' => $amount,
            'status_id' => TransactionStatusEnums::PENDING
        ]);
    }

    public function testCreatedTransactionWithoutExistingStatus()
    {
        $amount = 1000;
        $person = User::factory()->person()->create();
        $company = User::factory()->company()->create();
        $personWallet = Wallet::factory()->for($person)->create();
        $companyWallet = Wallet::factory()->for($company)->create();

        $this->expectExceptionMessage("200 is not a valid backing value for enum App\Modules\Transaction\Enums\TransactionStatusEnums");
        Transaction::query()->create([
            'origin_wallet_id' => $personWallet->id,
            'destination_wallet_id' => $companyWallet->id,
            'amount' => $amount,
            'status_id' => 200
        ]);
    }
}
