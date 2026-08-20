<?php

use function Livewire\Volt\{layout};
use Illuminate\Support\Facades\Auth;

layout('components.layouts.admin');

$logout = function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    $this->redirect('/admin/login', navigate: true);
};

?>

<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="font-display font-semibold text-2xl">Panel de administración</h1>
        <button wire:click="logout"
                class="font-mono text-xs px-4 py-2 border border-line rounded-sm hover:border-stamp hover:text-stamp transition">
            Cerrar sesión
        </button>
    </div>

    <p class="text-paper-dim font-mono text-sm">
        Login funcionando ✓ — el próximo paso es el CRUD de proyectos acá adentro.
    </p>
</div>