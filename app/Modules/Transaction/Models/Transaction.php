<?php

namespace App\Modules\Transaction\Models;

use App\Modules\Transaction\Enums\TransactionStatusEnums;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $origin_wallet_id
 * @property string $destination_wallet_id
 * @property int $amount
 * @property int $status_id
 * @property User $person
 * @property User $company
 * @property string $notes
 */
class Transaction extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $fillable = [
        'origin_wallet_id',
        'destination_wallet_id',
        'amount',
        'status_id',
        'notes'
    ];

    protected $casts = [
        'amount' => 'integer',
        'status_id' => TransactionStatusEnums::class
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(User::class, 'origin_wallet_id', 'id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'destination_wallet_id', 'id');
    }
}
