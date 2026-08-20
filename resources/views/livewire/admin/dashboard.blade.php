<?php

use function Livewire\Volt\{layout, state, with};
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

layout('components.layouts.admin');

state(['confirmingDeleteId' => null]);

with(['projects' => fn () => Project::ordered()->get()]);

$logout = function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    $this->redirect('/admin/login', navigate: true);
};

$confirmDelete = function ($id) {
    $this->confirmingDeleteId = $id;
};

$cancelDelete = function () {
    $this->confirmingDeleteId = null;
};

$delete = function ($id) {
    Project::findOrFail($id)->delete();
    $this->confirmingDeleteId = null;
};

?>

<div>
    <div class="flex justify-between items-center mb-10">
        <div>
            <h1 class="font-display font-semibold text-2xl">Panel de administración</h1>
            <p class="font-mono text-xs text-paper-dim mt-1">{{ $projects->count() }} proyecto(s)</p>
        </div>
        <div class="flex gap-3">
            <a href="/admin/profile" wire:navigate
               class="font-mono text-xs px-4 py-2.5 border border-line rounded-sm hover:border-stamp hover:text-stamp transition">
                Editar perfil
            </a>
            <a href="/admin/projects/create" wire:navigate
               class="font-mono text-xs px-4 py-2.5 rounded-sm bg-stamp text-ink font-bold hover:bg-emerald-500 transition">
                + Nuevo proyecto
            </a>
            <button wire:click="logout"
                    class="font-mono text-xs px-4 py-2.5 border border-line rounded-sm hover:border-stamp hover:text-stamp transition">
                Cerrar sesión
            </button>
        </div>
    </div>

    <div class="space-y-3">
        @forelse ($projects as $project)
            <div class="flex items-center justify-between bg-surface border border-line rounded px-5 py-4 gap-4">
                <div class="min-w-0">
                    <div class="font-mono text-xs text-paper-dim">
                        Ficha {{ str_pad($project->ficha_number, 3, '0', STR_PAD_LEFT) }} — {{ $project->category }}
                        @unless ($project->published)
                            <span class="ml-2 text-ticket">· oculto</span>
                        @endunless
                    </div>
                    <div class="font-display font-semibold truncate">{{ $project->title }}</div>
                </div>
                <div class="flex gap-2 items-center flex-shrink-0">
                    <a href="/admin/projects/{{ $project->id }}/edit" wire:navigate
                       class="font-mono text-xs px-3 py-1.5 border border-line rounded-sm hover:border-stamp hover:text-stamp transition">
                        Editar
                    </a>

                    @if ($confirmingDeleteId === $project->id)
                        <button wire:click="delete({{ $project->id }})"
                                class="font-mono text-xs px-3 py-1.5 bg-red-900/60 text-red-200 rounded-sm">
                            Confirmar
                        </button>
                        <button wire:click="cancelDelete"
                                class="font-mono text-xs px-3 py-1.5 border border-line rounded-sm">
                            Cancelar
                        </button>
                    @else
                        <button wire:click="confirmDelete({{ $project->id }})"
                                class="font-mono text-xs px-3 py-1.5 border border-red-900 text-red-400 rounded-sm hover:bg-red-950/30 transition">
                            Eliminar
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <p class="font-mono text-sm text-paper-dim">Todavía no hay proyectos cargados.</p>
        @endforelse
    </div>
</div>