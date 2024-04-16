<?php

namespace App\Modules\Transaction\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Transaction\Interfaces\TransactionServiceInterface;
use App\Modules\Transaction\Requests\CreateTransactionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CreateTransactionController extends Controller
{
    public function __construct(
        public readonly TransactionServiceInterface $transactionService
    )
    {
    }

    public function __invoke(CreateTransactionRequest $request): JsonResponse
    {
        $this->transactionService->execute($request->toDTO());

        return response()->json(["Transaction completed successfully"], Response::HTTP_CREATED);
    }
}
