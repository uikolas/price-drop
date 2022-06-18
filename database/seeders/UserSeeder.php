<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create(
            [
                'name' => 'test',
                'email' => 'test@test.com',
                'password' => Hash::make('123456')
            ]
        );
    }
}
