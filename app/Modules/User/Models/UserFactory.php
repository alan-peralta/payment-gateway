<?php

namespace App\Modules\User\Models;

use App\Modules\User\Enums\UserTypeEnums;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;
    protected static ?string $password;
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function person(): UserFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type_id' => UserTypeEnums::PERSON,
                'document_number' => fake()->cpf(false)
            ];
        });
    }

    public function company(): UserFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type_id' => UserTypeEnums::COMPANY,
                'document_number' => fake()->cnpj(),
            ];
        });
    }
}
