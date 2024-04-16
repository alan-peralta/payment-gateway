<?php

namespace App\Modules\Wallet\Models;

use App\Modules\User\Enums\UserTypeEnums;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $user_id
 * @property int $balance
 * @property User $user
 */
class Wallet extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'balance',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isCompany(): bool
    {
        return $this->user->user_type_id->value == UserTypeEnums::COMPANY->value;
    }

    protected static function newFactory(): WalletFactory
    {
        return WalletFactory::new();
    }
}
