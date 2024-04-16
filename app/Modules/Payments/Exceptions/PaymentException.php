<?php

namespace App\Modules\Payments\Exceptions;

use Exception;

class PaymentException extends Exception
{
    public static function methodNotImplemented(): self
    {
        return new static('Payment method not implemented');
    }

    public static function unableAuthorizePayment(): self
    {
        return new static('Unable to authorize payment');
    }
}
