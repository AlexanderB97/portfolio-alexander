<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('ficha_number'); // 001, 002, 003...
            $table->string('category'); // "SISTEMA DE GESTIÓN", "DIRECTORIO DIGITAL", etc.
            $table->string('title');
            $table->string('status_label'); // "Presentado ✓", "Desplegado ✓", "Académico"
            $table->enum('status_color', ['stamp', 'ticket'])->default('stamp'); // verde o ámbar
            $table->text('description');
            $table->string('role_label')->default('Mi rol'); // "Mi rol" o "Enfoque"
            $table->text('role_description');
            $table->json('meta'); // [{"label": "Equipo", "value": "4 personas"}, ...]
            $table->json('tags'); // ["Laravel 12", "Livewire 3", ...]
            $table->string('repo_url')->nullable();
            $table->string('demo_url')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};