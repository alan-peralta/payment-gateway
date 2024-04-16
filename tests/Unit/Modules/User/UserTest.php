<?php

namespace Modules\User;


use App\Modules\User\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;


    public function testCreatePersonUser()
    {
        $user = User::factory()->person()->create();

        $this->assertDatabaseHas('users', [
            'user_type_id' => $user->user_type_id,
            'document_number' => $user->document_number,
        ]);
    }

    public function testCreateUserWithEmailDuplicate()
    {
        $this->expectException(UniqueConstraintViolationException::class);
        $user = User::factory()->person()->create();
        User::factory()->person()->create([
            'email' => $user->email,
        ]);
    }

    public function testCreatePersonUserWithDocumentNumberDuplicate()
    {
        $this->expectException(UniqueConstraintViolationException::class);
        $user = User::factory()->person()->create();
        User::factory()
            ->person()
            ->state([
                'document_number' => $user->document_number,
            ])
            ->create();
    }

    public function testCreatePersonCompany()
    {
        $user = User::factory()->company()->create();

        $this->assertDatabaseHas('users', [
            'user_type_id' => $user->user_type_id,
            'document_number' => $user->document_number,
        ]);
    }

    public function testCreateCompanyUserWithDocumentNumberDuplicate()
    {
        $this->expectException(UniqueConstraintViolationException::class);
        $user = User::factory()->company()->create();
        User::factory()
            ->company()
            ->state([
                'document_number' => $user->document_number,
            ])
            ->create();
    }

    public function testCreateUserWithInvalidType()
    {
        $this->expectExceptionMessage('999 is not a valid backing value for enum App\Modules\User\Enums\UserTypeEnums');
        User::factory()
            ->create([
                'user_type_id' => 999,
                'document_number' => fake()->cpf(),
            ]);
    }
}
