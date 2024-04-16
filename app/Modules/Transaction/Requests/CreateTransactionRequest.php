<?php

namespace App\Modules\Transaction\Requests;

use App\Modules\Transaction\DTO\TransactionDTO;
use App\Modules\Transaction\Enums\TransactionStatusEnums;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payer' => [
                'required',
                Rule::exists('wallets', 'id')
            ],
            'payee' => [
                'required',
                Rule::exists('wallets', 'id')
            ],
            'amount' => [
                'required',
                'integer'
            ]
        ];
    }

    public function toDTO(): TransactionDTO
    {
        return new TransactionDTO(
            amount: $this->input('amount'),
            payer: $this->input('payer'),
            payee: $this->input('payee'),
        );

    }
}
