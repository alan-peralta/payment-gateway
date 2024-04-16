<?php

namespace App\Modules\Transaction\Services;

use App\Modules\Notification\Events\PaymentProcessed;
use App\Modules\Notification\Interfaces\NotificationServiceInterfaces;
use App\Modules\Payments\Exceptions\PaymentException;
use App\Modules\Payments\Interfaces\PaymentAuthorizationServiceInterface;
use App\Modules\Transaction\DTO\TransactionDTO;
use App\Modules\Transaction\Enums\TransactionStatusEnums;
use App\Modules\Transaction\Exceptions\TransactionException;
use App\Modules\Transaction\Interfaces\TransactionServiceInterface;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\Wallet\Interfaces\DepositWalletServiceInterface;
use App\Modules\Wallet\Interfaces\WithdrawalWalletServiceInterface;
use App\Modules\Wallet\Models\Wallet;
use Illuminate\Support\Facades\DB;

readonly class TransactionService implements TransactionServiceInterface
{
    public function __construct(
        public CreateTransactionService             $createTransactionService,
        public UpdateStatusTransactionService       $updateTransactionService,
        public PaymentAuthorizationServiceInterface $paymentAuthorizationService,
        public NotificationServiceInterfaces        $notificationService,
        public WithdrawalWalletServiceInterface     $withdrawalWalletService,
        public DepositWalletServiceInterface        $depositWalletService
    )
    {
    }

    /**
     * @throws PaymentException
     * @throws TransactionException
     */
    public function execute(TransactionDTO $transactionDTO): void
    {
        $transaction = $this->createTransactionService->execute($transactionDTO);

        try {
            DB::beginTransaction();

            $this->validatePaymentRules($transactionDTO, $transaction);

            $this->withdrawalWalletService->execute($transactionDTO->payer, $transactionDTO->amount);
            $this->depositWalletService->execute($transactionDTO->payee, $transactionDTO->amount);

            $authorized = $this->paymentAuthorizationService->execute($transaction);
            if (!$authorized) {
                throw PaymentException::unableAuthorizePayment();
            }

            $this->updateTransactionService->execute($transaction, TransactionStatusEnums::APPROVED, 'Payment authorized');

            DB::commit();
        } catch (TransactionException | PaymentException $exception) {
            DB::rollBack();

            $this->updateTransactionService->execute($transaction, TransactionStatusEnums::CANCELLED, $exception->getMessage());

            throw $exception;
        }


        PaymentProcessed::dispatch($transactionDTO);

    }

    /**
     * @throws TransactionException
     */
    private function validatePaymentRules(TransactionDTO $transactionDTO, Transaction $transaction): void
    {
        /** @var Wallet $walletPayer */
        $walletPayer = Wallet::query()->find($transactionDTO->payer);
        if ($walletPayer->isCompany()) {
            throw TransactionException::userTypeNotAllowed();
        }

        if ($walletPayer->balance < $transactionDTO->amount) {
            throw TransactionException::insufficientFunds();
        }
    }
}
