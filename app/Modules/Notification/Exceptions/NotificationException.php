<?php

namespace App\Modules\Notification\Exceptions;

use Exception;

class NotificationException extends Exception
{
    public static function methodNotImplemented(): self
    {
        return new self('Method not implemented.');
    }

}
