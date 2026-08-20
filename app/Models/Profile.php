<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profile';

    protected $fillable = [
        'name',
        'tagline',
        'bio',
        'avatar_path',
        'email',
        'github_url',
        'linkedin_url',
        'available_for_work',
        'stats',
    ];

    protected $casts = [
        'stats' => 'array',
        'available_for_work' => 'boolean',
    ];

    /**
     * Siempre hay un solo registro de perfil. Si no existe, lo crea con valores por defecto.
     */
    public static function current(): self
    {
        return static::first() ?? static::create([
            'tagline' => 'Construyo sistemas que administran negocios reales.',
            'bio' => 'Desarrollador full stack especializado en Laravel + Livewire.',
            'stats' => [
                ['number' => '3', 'label' => 'Sistemas en producción'],
                ['number' => '3', 'label' => 'Equipos integrados'],
                ['number' => '12+', 'label' => 'Bugs de prod resueltos'],
                ['number' => '01', 'label' => 'TIF en curso'],
            ],
        ]);
    }
}