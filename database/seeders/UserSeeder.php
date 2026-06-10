<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'saad.salman@reliable.com'],
            [
                'name' => 'Saad Salman',
                'password' => 'Password@1234',
                'phone' => null,
                'status' => true,
            ]
        );
    }
}
