<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::truncate();

        Project::create([
            'ficha_number' => 1,
            'category' => 'SISTEMA DE GESTIÓN',
            'title' => 'Estética Mascotas',
            'status_label' => 'Presentado ✓',
            'status_color' => 'stamp',
            'description' => 'Sistema de gestión para peluquería y estética de mascotas, desarrollado en equipo de 4. Cubre turnos, clientes, historial de mascotas y control de acceso por rol.',
            'role_label' => 'Mi rol',
            'role_description' => 'Responsable de 3 épicas: estabilización del repo, roles y autorización (Gates/Policies), y refactor del modelo de datos de clientes. Diagnostiqué y resolví fallos de producción, incluyendo assets de Livewire devolviendo 404 y migraciones duplicadas por merges de rama.',
            'meta' => [
                ['label' => 'Equipo', 'value' => '4 personas'],
                ['label' => 'Rol', 'value' => 'Backend / Auth lead'],
                ['label' => 'Estado', 'value' => 'Producción'],
            ],
            'tags' => ['Laravel 12', 'Livewire 3', 'Volt', 'Tailwind', 'MySQL'],
            'repo_url' => null,
            'demo_url' => null,
            'order' => 1,
        ]);

        Project::create([
            'ficha_number' => 2,
            'category' => 'DIRECTORIO DIGITAL',
            'title' => 'ARGart',
            'status_label' => 'Desplegado ✓',
            'status_color' => 'stamp',
            'description' => 'Directorio comercial digital — una guía de negocios moderna, tipo "páginas amarillas" — desplegado en servidor Linux con panel de administración propio.',
            'role_label' => 'Mi rol',
            'role_description' => 'Diseño de base de datos, endpoints de backend, panel admin y coordinación de equipo. Resolví bugs de rutas de Storage en subdirectorios, conflictos de mayúsculas/minúsculas en Linux, integración de Google Maps y conflictos de z-index entre modales y un widget de WhatsApp.',
            'meta' => [
                ['label' => 'Infra', 'value' => 'Servidor Linux'],
                ['label' => 'Rol', 'value' => 'Backend / DB / Coord.'],
                ['label' => 'Estado', 'value' => 'En producción'],
            ],
            'tags' => ['Laravel', 'MySQL', 'Google Maps API', 'Bootstrap'],
            'repo_url' => null,
            'demo_url' => null,
            'order' => 2,
        ]);

        Project::create([
            'ficha_number' => 3,
            'category' => 'SISTEMAS DE BASE DE DATOS',
            'title' => 'Biblioteca — MySQL 8.0',
            'status_label' => 'Académico',
            'status_color' => 'ticket',
            'description' => 'Sistema de gestión bibliotecaria para la cursada de Base de Datos. Benchmark de índices (simples, compuestos, FULLTEXT), normalización hasta 4NF/5NF, datos semiestructurados en XML/JSON y migración a MongoDB.',
            'role_label' => 'Enfoque',
            'role_description' => 'Todo el trabajo anclado a un esquema SQL real subido por la cátedra: desde diseño relacional hasta scripts de conversión Python y migración con mongosh.',
            'meta' => [
                ['label' => 'Motor', 'value' => 'MySQL 8.0'],
                ['label' => 'NoSQL', 'value' => 'MongoDB'],
                ['label' => 'Normalización', 'value' => 'Hasta 5NF'],
            ],
            'tags' => ['MySQL', 'MongoDB', 'XML/DTD', 'Python'],
            'repo_url' => null,
            'demo_url' => null,
            'order' => 3,
        ]);
    }
}