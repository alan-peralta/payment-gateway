<?php

namespace App\Modules\Wallet\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
class WalletFactory extends Factory
{
    protected $model = Wallet::class;
    public function definition(): array
    {
        return [
            'balance' => fake()->randomNumber(4, 1000),
        ];
    }
}
