<?php

namespace App\Modules\Transaction\Enums;

enum TransactionStatusEnums: int
{

    case PENDING = 1;
    case APPROVED = 2;
    case CANCELLED = 3;
}
