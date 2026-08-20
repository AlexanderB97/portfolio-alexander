<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Benitez José Alexander');
            $table->string('tagline'); // "Construyo sistemas que administran negocios reales."
            $table->text('bio');
            $table->string('avatar_path')->nullable();
            $table->string('email')->nullable();
            $table->string('github_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->boolean('available_for_work')->default(true);
            $table->json('stats')->nullable(); // [{"number": "3", "label": "Sistemas en producción"}, ...]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile');
    }
};