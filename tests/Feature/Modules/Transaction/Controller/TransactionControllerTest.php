<?php

namespace Modules\Transaction\Controller;

use App\Modules\Payments\Exceptions\PaymentException;
use App\Modules\Transaction\Enums\TransactionStatusEnums;
use App\Modules\Transaction\Exceptions\TransactionException;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\User\Models\User;
use App\Modules\Wallet\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateTransaction()
    {
        $this->withoutExceptionHandling();
        $amount = 1000;
        $userPayer = User::factory()->person()->create();
        $walletPayer = Wallet::factory()->for($userPayer)->create([
            'balance' => $amount
        ]);
        $userPayee = User::factory()->company()->create();
        $walletPayee = Wallet::factory()->for($userPayee)->create([
            'balance' => 0
        ]);

        $response = $this->postJson(route('transaction.create'), [
            'amount' => $amount,
            'payer' => $walletPayer->id,
            'payee' => $walletPayee->id,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('transactions', [
            'amount' => $amount,
            'origin_wallet_id' => $walletPayer->id,
            'destination_wallet_id' => $walletPayee->id,
            'status_id' => TransactionStatusEnums::APPROVED
        ]);

        $this->assertDatabaseHas('wallets', [
                'id' => $walletPayer->id,
                'balance' => 0
        ]);

        $this->assertDatabaseHas('wallets', [
            'id' => $walletPayee->id,
            'balance' => $amount
        ]);
    }

    public function testCreateTransactionWithoutPaymentMethodNotImplemented()
    {

        Config::set('payments.default', 'ypto');
        $userPayer = User::factory()->person()->create();
        $walletPayer = Wallet::factory()->for($userPayer)->create();
        $userPayee = User::factory()->company()->create();
        $walletPayee = Wallet::factory()->for($userPayee)->create();
        $amount = 1000;

        $this->withoutExceptionHandling();
        $this->expectException(PaymentException::class);
        $this->expectExceptionMessage(PaymentException::methodNotImplemented()->getMessage());

        $this->postJson(route('transaction.create'), [
            'amount' => $amount,
            'payer' => $walletPayer->id,
            'payee' => $walletPayee->id,
        ]);
    }

    public function testCreateTransactionWithExceptionUnableAuthorizePayment()
    {
        Http::fake([
            '*run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc' => Http::response([
                'message' => 'Não Autorizado'
            ])
        ]);

        $userPayer = User::factory()->person()->create();
        $walletPayer = Wallet::factory()->for($userPayer)->create();
        $userPayee = User::factory()->company()->create();
        $walletPayee = Wallet::factory()->for($userPayee)->create();
        $amount = 1000;

        $this->withoutExceptionHandling();
        $this->expectException(PaymentException::class);
        $this->expectExceptionMessage(PaymentException::unableAuthorizePayment()->getMessage());

        $this->postJson(route('transaction.create'), [
            'amount' => $amount,
            'payer' => $walletPayer->id,
            'payee' => $walletPayee->id,
        ]);

    }

    public function testCreateTransactionWithExceptionUnableAuthorizePaymentAndTransactionCancelled()
    {
        Http::fake([
            '*run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc' => Http::response([
                'message' => 'Não Autorizado'
            ])
        ]);

        $amount = 1000;
        $userPayer = User::factory()->person()->create();
        $walletPayer = Wallet::factory()->for($userPayer)->create();
        $userPayee = User::factory()->company()->create();
        $walletPayee = Wallet::factory()->for($userPayee)->create();


        $this->postJson(route('transaction.create'), [
            'amount' => $amount,
            'payer' => $walletPayer->id,
            'payee' => $walletPayee->id,
        ]);

        $this->assertDatabaseHas('transactions', [
            'amount' => $amount,
            'origin_wallet_id' => $walletPayer->id,
            'destination_wallet_id' => $walletPayee->id,
            'status_id' => TransactionStatusEnums::CANCELLED
        ]);

    }

    public function testCreateTransactionWithExceptionUserTypeNotAllowed()
    {
        $amount = 1000;
        $userPayer = User::factory()->company()->create();
        $walletPayer = Wallet::factory()->for($userPayer)->create();
        $userPayee = User::factory()->person()->create();
        $walletPayee = Wallet::factory()->for($userPayee)->create();

        $this->withoutExceptionHandling();
        $this->expectException(TransactionException::class);
        $this->expectExceptionMessage(TransactionException::userTypeNotAllowed()->getMessage());

        $this->postJson(route('transaction.create'), [
            'amount' => $amount,
            'payer' => $walletPayer->id,
            'payee' => $walletPayee->id,
        ]);
    }

    public function testCreateTransactionWithExceptionUserTypeNotAllowedAndTransactionStatusIsCancelled()
    {
        $amount = 1000;
        $userPayer = User::factory()->company()->create();
        $walletPayer = Wallet::factory()->for($userPayer)->create();
        $userPayee = User::factory()->person()->create();
        $walletPayee = Wallet::factory()->for($userPayee)->create();

        $this->withoutExceptionHandling();
        $this->expectException(TransactionException::class);
        $this->expectExceptionMessage(TransactionException::userTypeNotAllowed()->getMessage());

        $this->postJson(route('transaction.create'), [
            'amount' => $amount,
            'payer' => $walletPayer->id,
            'payee' => $walletPayee->id,
        ]);

        $this->assertDatabaseHas('transactions', [
            'amount' => $amount,
            'origin_wallet_id' => $walletPayee->id,
            'destination_wallet_id' => $walletPayer->id,
            'status_id' => TransactionStatusEnums::CANCELLED,
            'notes' => TransactionException::userTypeNotAllowed()->getMessage()
        ]);
    }

    public function testCreateTransactionWithTransactionExceptionInsufficientFunds()
    {
        $amount = 1000;
        $userPayer = User::factory()->person()->create();
        $walletPayer = Wallet::factory()->for($userPayer)->create([
            'balance' => 100
        ]);
        $userPayee = User::factory()->company()->create();
        $walletPayee = Wallet::factory()->for($userPayee)->create();

        $this->withoutExceptionHandling();
        $this->expectException(TransactionException::class);
        $this->expectExceptionMessage(TransactionException::insufficientFunds()->getMessage());

        $this->postJson(route('transaction.create'), [
            'amount' => $amount,
            'payer' => $walletPayer->id,
            'payee' => $walletPayee->id,
        ]);
    }

    public function testCreateTransactionWithPayerInsufficientFundsAndTransactionStatusCancelled()
    {
        $amount = 1000;
        $userPayer = User::factory()->person()->create();
        $walletPayer = Wallet::factory()->for($userPayer)->create([
            'balance' => 100
        ]);
        $userPayee = User::factory()->company()->create();
        $walletPayee = Wallet::factory()->for($userPayee)->create();


        $this->postJson(route('transaction.create'), [
            'amount' => $amount,
            'payer' => $walletPayer->id,
            'payee' => $walletPayee->id,
        ]);

        $this->assertDatabaseHas('transactions', [
            'amount' => $amount,
            'origin_wallet_id' => $walletPayer->id,
            'destination_wallet_id' => $walletPayee->id,
            'status_id' => TransactionStatusEnums::CANCELLED,
            'notes' => TransactionException::insufficientFunds()->getMessage()
        ]);
    }
}
