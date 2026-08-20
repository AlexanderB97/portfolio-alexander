<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@example.com')],
            [
                'name' => 'Alexander',
                // El modelo User ya castea 'password' como 'hashed' por defecto en Laravel 12,
                // así que NO hay que hashearlo acá manualmente (si no, queda doble-hasheado).
                'password' => env('ADMIN_PASSWORD', 'changeme123'),
            ]
        );
    }
}