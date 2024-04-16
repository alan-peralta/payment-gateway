<?php

namespace App\Modules\User\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\User\Enums\UserTypeEnums;
use App\Modules\Wallet\Models\Wallet;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property string id
 * @property UserTypeEnums user_type_id
 * @property string name
 * @property string email
 * @property string password
 * @property string document_number
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    public $incrementing = false;
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type_id',
        'document_number'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'user_type_id' => UserTypeEnums::class,
        ];
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function personTransaction(): HasMany
    {
        return $this->hasMany(Transaction::class, 'origin_wallet_id', 'id');
    }

    public function companyTransaction(): HasMany
    {
        return $this->hasMany(Transaction::class, 'destination_wallet_id', 'id');
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
