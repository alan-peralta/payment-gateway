<?php

namespace App\Modules\Transaction\Exceptions;

use Exception;

class TransactionException extends Exception
{
    public static function userTypeNotAllowed(): self
    {
        return new static('User type not allowed to make this payment');
    }

    public static function insufficientFunds(): self
    {
        return new static('Insufficient funds');
    }
}
