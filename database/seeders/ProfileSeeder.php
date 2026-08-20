<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        Profile::truncate();

        Profile::create([
            'name' => 'Alexander',
            'tagline' => 'Construyo sistemas que administran negocios reales.',
            'bio' => 'Desarrollador full stack especializado en Laravel + Livewire. De la primera migración a producción estable: roles, datos, deploys — el trabajo que sostiene un sistema en uso.',
            'avatar_path' => null,
            'email' => null,
            'github_url' => null,
            'linkedin_url' => null,
            'available_for_work' => true,
            'stats' => [
                ['number' => '3', 'label' => 'Sistemas en producción'],
                ['number' => '4', 'label' => 'Equipos integrados'],
                ['number' => '12+', 'label' => 'Bugs de prod resueltos'],
                ['number' => '01', 'label' => 'TIF en curso'],
            ],
        ]);
    }
}