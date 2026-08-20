<?php

use function Livewire\Volt\{state, layout};
use Illuminate\Support\Facades\Auth;

layout('components.layouts.app');

state(['email' => '', 'password' => '', 'error' => '']);

$login = function () {
    $this->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
        request()->session()->regenerate();
        $this->redirect('/admin', navigate: true);
        return;
    }

    $this->error = 'Credenciales incorrectas.';
};

?>

<div class="min-h-screen flex items-center justify-center px-6">
    <div class="w-full max-w-sm">
        <div class="font-mono text-sm mb-8 text-center">
            alexander<span class="text-stamp">.dev</span> <span class="text-paper-dim">/admin</span>
        </div>

        <div class="bg-surface border border-line rounded p-8">
            <h1 class="font-display font-semibold text-xl mb-6">Acceso administrador</h1>

            @if ($error)
                <div class="font-mono text-xs text-red-400 border border-red-900 bg-red-950/30 rounded px-3 py-2 mb-4">
                    {{ $error }}
                </div>
            @endif

            <form wire:submit="login" class="space-y-4">
                <div>
                    <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">Email</label>
                    <input type="email" wire:model="email"
                           class="w-full bg-ink border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp">
                    @error('email') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">Contraseña</label>
                    <input type="password" wire:model="password"
                           class="w-full bg-ink border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp">
                    @error('password') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <button type="submit"
                        class="w-full font-mono text-sm px-6 py-3 rounded-sm bg-stamp text-ink font-bold hover:bg-emerald-500 transition">
                    Ingresar
                </button>
            </form>
        </div>
    </div>
</div>