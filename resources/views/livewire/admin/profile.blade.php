<?php

use function Livewire\Volt\{state, mount, uses, layout};
use Livewire\WithFileUploads;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

layout('components.layouts.admin');
uses([WithFileUploads::class]);

state([
    'profileId' => null,
    'name' => '',
    'tagline' => '',
    'bio' => '',
    'email' => '',
    'github_url' => '',
    'linkedin_url' => '',
    'available_for_work' => true,
    'stats' => [],
    'photo' => null,
    'currentAvatar' => null,
]);

mount(function () {
    $profile = Profile::current();

    $this->profileId = $profile->id;
    $this->name = $profile->name;
    $this->tagline = $profile->tagline;
    $this->bio = $profile->bio;
    $this->email = $profile->email;
    $this->github_url = $profile->github_url;
    $this->linkedin_url = $profile->linkedin_url;
    $this->available_for_work = $profile->available_for_work;
    $this->currentAvatar = $profile->avatar_path;

    $this->stats = $profile->stats ?: [
        ['number' => '', 'label' => ''],
        ['number' => '', 'label' => ''],
        ['number' => '', 'label' => ''],
        ['number' => '', 'label' => ''],
    ];

    // aseguramos siempre 4 filas para el formulario, aunque haya menos guardadas
    while (count($this->stats) < 4) {
        $this->stats[] = ['number' => '', 'label' => ''];
    }
});

$save = function () {
    $this->validate([
        'name' => 'required|string|max:255',
        'tagline' => 'required|string|max:255',
        'bio' => 'required|string',
        'email' => 'nullable|email',
        'github_url' => 'nullable|url',
        'linkedin_url' => 'nullable|url',
        'photo' => 'nullable|image|max:2048',
    ]);

    $profile = Profile::findOrFail($this->profileId);

    $data = [
        'name' => $this->name,
        'tagline' => $this->tagline,
        'bio' => $this->bio,
        'email' => $this->email ?: null,
        'github_url' => $this->github_url ?: null,
        'linkedin_url' => $this->linkedin_url ?: null,
        'available_for_work' => $this->available_for_work,
        'stats' => collect($this->stats)
            ->filter(fn ($s) => filled($s['number']) || filled($s['label']))
            ->values()
            ->all(),
    ];

    if ($this->photo) {
        if ($profile->avatar_path) {
            Storage::disk('public')->delete($profile->avatar_path);
        }
        $data['avatar_path'] = $this->photo->store('avatars', 'public');
    }

    $profile->update($data);

    $this->redirect('/admin/profile', navigate: true);
};

?>

<div class="max-w-2xl">
    <div class="flex items-center gap-4 mb-8">
        <a href="/admin" wire:navigate class="font-mono text-xs text-paper-dim hover:text-stamp transition">← Volver</a>
    </div>

    <h1 class="font-display font-semibold text-2xl mb-8">Editar perfil</h1>

    <form wire:submit="save" class="space-y-6">

        {{-- FOTO --}}
        <div>
            <label class="font-mono text-[11px] uppercase text-paper-dim block mb-2">Foto de perfil</label>
            <div class="flex items-center gap-4">
                @if ($photo)
                    <img src="{{ $photo->temporaryUrl() }}" class="w-16 h-16 rounded-full object-cover border-2 border-stamp">
                @elseif ($currentAvatar)
                    <img src="{{ Storage::url($currentAvatar) }}" class="w-16 h-16 rounded-full object-cover border-2 border-line">
                @else
                    <div class="w-16 h-16 rounded-full bg-surface-2 border border-line flex items-center justify-center font-mono text-[10px] text-paper-dim">
                        sin foto
                    </div>
                @endif
                <input type="file" wire:model="photo" accept="image/*"
                       class="font-mono text-xs text-paper-dim file:mr-3 file:py-2 file:px-3 file:rounded-sm file:border file:border-line file:bg-surface file:text-paper file:font-mono file:text-xs hover:file:border-stamp">
            </div>
            <div wire:loading wire:target="photo" class="font-mono text-[11px] text-stamp mt-1.5">Subiendo...</div>
            @error('photo') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">Nombre</label>
            <input type="text" wire:model="name" class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp">
            @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">Tagline (título del hero)</label>
            <input type="text" wire:model="tagline" class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp">
            @error('tagline') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">Bio (párrafo debajo del tagline)</label>
            <textarea wire:model="bio" rows="3" class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp"></textarea>
            @error('bio') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
        </div>

        <label class="flex items-center gap-2 font-mono text-xs text-paper-dim">
            <input type="checkbox" wire:model="available_for_work" class="accent-stamp">
            Mostrar "Disponible para proyectos"
        </label>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">Email de contacto</label>
                <input type="text" wire:model="email" placeholder="vos@ejemplo.com" class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp">
                @error('email') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">GitHub URL</label>
                <input type="text" wire:model="github_url" placeholder="https://github.com/tuusuario" class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp">
                @error('github_url') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">LinkedIn URL</label>
            <input type="text" wire:model="linkedin_url" placeholder="https://linkedin.com/in/tuusuario" class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp">
            @error('linkedin_url') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- STATS DEL HERO --}}
        <div>
            <label class="font-mono text-[11px] uppercase text-paper-dim block mb-2">Stats del hero (4 números)</label>
            <div class="grid grid-cols-2 gap-3">
                @foreach ($stats as $i => $stat)
                    <div class="bg-surface border border-line rounded-sm p-3 flex gap-2">
                        <input type="text" wire:model="stats.{{ $i }}.number" placeholder="3"
                               class="w-16 bg-ink border border-line rounded-sm px-2 py-1.5 text-sm font-mono text-center focus:outline-none focus:border-stamp">
                        <input type="text" wire:model="stats.{{ $i }}.label" placeholder="Sistemas en producción"
                               class="flex-1 bg-ink border border-line rounded-sm px-2 py-1.5 text-xs focus:outline-none focus:border-stamp">
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="font-mono text-sm px-6 py-3 rounded-sm bg-stamp text-ink font-bold hover:bg-emerald-500 transition">
                Guardar cambios
            </button>
            <a href="/admin" wire:navigate class="font-mono text-sm px-6 py-3 rounded-sm border border-line hover:border-stamp hover:text-stamp transition">
                Cancelar
            </a>
        </div>
    </form>
</div>