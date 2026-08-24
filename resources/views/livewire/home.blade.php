<?php

use App\Models\Project;
use App\Models\Profile;
use App\Models\ContactMessage;
use App\Mail\NewContactMessage;
use Illuminate\Support\Facades\Mail;
use function Livewire\Volt\{layout, with, state};

layout('components.layouts.app');

with([
    'profile' => fn() => Profile::current(),
    'projects' => fn() => Project::published()->ordered()->get(),
]);

state([
    'contactName' => '',
    'contactEmail' => '',
    'contactMessageText' => '',
    'contactSent' => false,
]);

$sendMessage = function () {
    $this->validate([
        'contactName' => 'required|string|max:255',
        'contactEmail' => 'required|email',
        'contactMessageText' => 'required|string|max:2000',
    ]);

    $contactMessage = ContactMessage::create([
        'name' => $this->contactName,
        'email' => $this->contactEmail,
        'message' => $this->contactMessageText,
    ]);

    $adminEmail = Profile::current()->email ?? config('mail.from.address');
    if ($adminEmail) {
        Mail::to($adminEmail)->send(new NewContactMessage($contactMessage));
    }

    $this->contactSent = true;
    $this->reset(['contactName', 'contactEmail', 'contactMessageText']);
};

?>

<div>
    {{-- fondo de grilla técnica --}}
    <div class="fixed inset-0 z-0 pointer-events-none opacity-10"
        style="background-image: linear-gradient(#343B47 1px, transparent 1px), linear-gradient(90deg, #343B47 1px, transparent 1px); background-size: 64px 64px;">
    </div>

    <div class="relative z-10">

        {{-- NAV --}}
        <nav class="sticky top-0 z-50 bg-ink/90 backdrop-blur border-b border-line">
            <div class="max-w-5xl mx-auto px-8 h-16 flex items-center justify-between">
                <div class="font-mono text-sm">{{ strtolower($profile->name) }}<span class="text-stamp">.dev</span></div>
                <div class="hidden md:flex gap-7 font-mono text-xs uppercase tracking-wide text-paper-dim">
                    <a href="#proyectos" class="hover:text-stamp transition"><span class="text-stamp">·/</span>Proyectos</a>
                    <a href="#stack" class="hover:text-stamp transition"><span class="text-stamp">·/</span>Stack</a>
                    <a href="#tif" class="hover:text-stamp transition"><span class="text-stamp">·/</span>TIF</a>
                    <a href="#contacto" class="hover:text-stamp transition"><span class="text-stamp">·/</span>Contacto</a>
                </div>
            </div>
        </nav>

        {{-- HERO --}}
        <header class="max-w-5xl mx-auto px-8 pt-32 pb-24">

            @if ($profile->avatar_path)
            <img src="{{ Storage::url($profile->avatar_path) }}"
                alt="{{ $profile->name }}"
                class="w-20 h-20 rounded-full object-cover border-2 border-stamp mb-6">
            @endif

            @if ($profile->available_for_work)
            <div class="inline-flex items-center gap-2 font-mono text-xs uppercase tracking-wide text-stamp border border-stamp-dim rounded-sm px-3 py-1.5 mb-7">
                <span class="text-[8px]">●</span> Disponible para proyectos
            </div>
            @endif

            <h1 class="font-display font-bold text-5xl md:text-7xl leading-[1.05] tracking-tight max-w-3xl">
                {{ $profile->tagline }}
            </h1>

            <p class="mt-6 text-lg text-paper-dim max-w-xl">
                {{ $profile->bio }}
            </p>

            <div class="flex gap-4 mt-10 flex-wrap">
                <a href="#proyectos" class="font-mono text-sm px-6 py-3.5 rounded-sm bg-stamp text-ink font-bold hover:-translate-y-0.5 hover:bg-emerald-500 transition">
                    Ver fichas de proyecto →
                </a>
                <a href="#contacto" class="font-mono text-sm px-6 py-3.5 rounded-sm border border-line hover:border-stamp hover:text-stamp hover:-translate-y-0.5 transition">
                    Hablemos
                </a>
            </div>

            @if ($profile->stats)
            <div class="grid grid-cols-2 md:grid-cols-4 border-t border-b border-line mt-20">
                @foreach ($profile->stats as $i => $stat)
                <div class="p-6 {{ !$loop->last ? 'border-r border-line' : '' }}">
                    <div class="font-display font-bold text-2xl">
                        {{ str_contains(strtolower($stat['label']), 'producci') ? $projects->where('status_color', 'stamp')->count() : $stat['number'] }}
                    </div>
                    <div class="font-mono text-[11px] uppercase text-paper-dim mt-1">{{ $stat['label'] }}</div>
                </div>
                @endforeach
            </div>
            @endif
        </header>

        {{-- PROYECTOS / FICHAS --}}
        <section id="proyectos" class="max-w-5xl mx-auto px-8 py-24">
            <div class="flex items-baseline gap-4 mb-14 flex-wrap">
                <span class="font-mono text-stamp text-sm">01</span>
                <h2 class="font-display font-semibold text-3xl">Fichas de proyecto</h2>
                <span class="font-mono text-xs text-paper-dim border border-line rounded-sm px-2.5 py-1">
                    {{ $projects->count() }} {{ Str::plural('proyecto', $projects->count()) }} publicado{{ $projects->count() === 1 ? '' : 's' }}
                </span>
                <div class="flex-1 h-px bg-line"></div>
            </div>

            @foreach ($projects as $project)
            <div class="bg-surface border border-line rounded hover:border-stamp-dim hover:-translate-y-1 transition mb-8 overflow-hidden">
                @if ($project->image_path)
                <img src="{{ Storage::url($project->image_path) }}"
                    alt="{{ $project->title }}"
                    class="w-full h-56 object-cover border-b border-line">
                @endif
                <div class="flex justify-between items-start px-8 pt-7 pb-5 border-b border-dashed border-line">
                    <div>
                        <div class="font-mono text-xs text-paper-dim tracking-wide">
                            FICHA N° <b class="text-paper">{{ str_pad($project->ficha_number, 3, '0', STR_PAD_LEFT) }}</b> — {{ $project->category }}
                        </div>
                        <div class="font-display font-semibold text-2xl mt-1.5">{{ $project->title }}</div>
                    </div>
                    @php
                    $isStamp = $project->status_color === 'stamp';
                    @endphp
                    <div class="font-mono text-[10px] uppercase tracking-widest px-2.5 py-1.5 border-[1.5px] rounded-sm whitespace-nowrap
                            {{ $isStamp ? 'border-stamp text-stamp rotate-3' : 'border-ticket text-ticket -rotate-2' }}">
                        {{ $project->status_label }}
                    </div>
                </div>
                <div class="grid md:grid-cols-[1.4fr_1fr] gap-8 px-8 pt-6 pb-8">
                    <div>
                        <p class="text-paper-dim text-[15px]">
                            {{ $project->description }}
                        </p>
                        <div class="border-l-2 border-stamp pl-3 mt-4">
                            <b class="block font-mono text-[11px] uppercase text-stamp mb-1">{{ $project->role_label }}</b>
                            <span class="text-sm">{{ $project->role_description }}</span>
                        </div>

                        @if ($project->repo_url || $project->demo_url)
                        <div class="flex gap-3 mt-5">
                            @if ($project->repo_url)
                            <a href="{{ $project->repo_url }}" target="_blank" rel="noopener"
                                class="font-mono text-xs px-4 py-2 rounded-sm border border-line hover:border-stamp hover:text-stamp transition inline-flex items-center gap-1.5">
                                Repositorio ↗
                            </a>
                            @endif
                            @if ($project->demo_url)
                            <a href="{{ $project->demo_url }}" target="_blank" rel="noopener"
                                class="font-mono text-xs px-4 py-2 rounded-sm bg-stamp-dim text-paper hover:bg-stamp hover:text-ink transition inline-flex items-center gap-1.5">
                                Ver demo ↗
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>
                    <div class="font-mono text-xs">
                        @foreach ($project->meta as $i => $row)
                        <div class="flex justify-between py-2 {{ !$loop->last ? 'border-b border-line' : '' }} text-paper-dim">
                            <span>{{ $row['label'] }}</span><span class="text-paper">{{ $row['value'] }}</span>
                        </div>
                        @endforeach
                        <div class="flex flex-wrap gap-2 mt-4">
                            @foreach ($project->tags as $tag)
                            <span class="text-[10px] border border-line rounded-sm px-2 py-1 text-paper-dim">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </section>

        {{-- STACK --}}
        <section id="stack" class="max-w-5xl mx-auto px-8 py-24">
            <div class="flex items-baseline gap-4 mb-14">
                <span class="font-mono text-stamp text-sm">02</span>
                <h2 class="font-display font-semibold text-3xl">Stack de trabajo</h2>
                <div class="flex-1 h-px bg-line"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-px bg-line border border-line">
                <div class="bg-surface p-6">
                    <div class="font-mono text-[10px] uppercase text-stamp tracking-widest mb-2.5">Backend</div>
                    <ul class="space-y-1 text-sm">
                        <li>— Laravel 12</li>
                        <li>— Livewire 3 + Volt</li>
                        <li>— PHP</li>
                    </ul>
                </div>
                <div class="bg-surface p-6">
                    <div class="font-mono text-[10px] uppercase text-stamp tracking-widest mb-2.5">Frontend</div>
                    <ul class="space-y-1 text-sm">
                        <li>— Tailwind CSS</li>
                        <li>— Alpine.js</li>
                        <li>— Blade</li>
                    </ul>
                </div>
                <div class="bg-surface p-6">
                    <div class="font-mono text-[10px] uppercase text-stamp tracking-widest mb-2.5">Datos</div>
                    <ul class="space-y-1 text-sm">
                        <li>— MySQL 8.0</li>
                        <li>— MongoDB</li>
                        <li>— Normalización avanzada</li>
                    </ul>
                </div>
                <div class="bg-surface p-6">
                    <div class="font-mono text-[10px] uppercase text-stamp tracking-widest mb-2.5">Flujo de equipo</div>
                    <ul class="space-y-1 text-sm">
                        <li>— Git por épica + PR</li>
                        <li>— Conventional Commits</li>
                        <li>— Code review</li>
                    </ul>
                </div>
            </div>
        </section>

        {{-- TIF --}}
        <section id="tif" class="max-w-5xl mx-auto px-8 py-24">
            <div class="flex items-baseline gap-4 mb-14">
                <span class="font-mono text-stamp text-sm">03</span>
                <h2 class="font-display font-semibold text-3xl">En curso ahora</h2>
                <div class="flex-1 h-px bg-line"></div>
            </div>

            <div class="bg-surface border border-stamp-dim rounded p-8 flex items-center justify-between gap-6 flex-wrap">
                <div class="max-w-lg">
                    <h3 class="font-display font-semibold text-xl mb-2">Trabajo Final Integrador — Tecnicatura en Programación</h3>
                    <p class="text-paper-dim text-sm">
                        Proyecto de cierre de carrera con intención comercial real, no solo académica: sistema de gestión (kiosco o clínica dental) con integración fiscal ARCA y cliente real en evaluación.
                    </p>
                </div>
                <div class="w-2.5 h-2.5 rounded-full bg-stamp flex-shrink-0 animate-pulse"></div>
            </div>
        </section>


        {{-- CONTACTO --}}
        <section id="contacto" class="max-w-5xl mx-auto px-8 py-24">
            <div class="max-w-lg mx-auto text-center">
                <h2 class="font-display font-bold text-4xl md:text-5xl mb-5">¿Tenés un sistema<br>que necesita construirse?</h2>
                <p class="text-paper-dim mb-9">Contame sobre tu proyecto y te respondo a la brevedad.</p>
            </div>

            <div class="max-w-lg mx-auto">
                @if ($contactSent)
                <div class="bg-surface border border-stamp-dim rounded p-6 text-center">
                    <div class="font-mono text-stamp text-sm mb-1">Mensaje enviado ✓</div>
                    <p class="text-paper-dim text-sm">Gracias por escribirme, te voy a responder pronto.</p>
                </div>
                @else
                <form wire:submit="sendMessage" class="space-y-4">
                    <div>
                        <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">Nombre</label>
                        <input type="text" wire:model="contactName"
                            class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp">
                        @error('contactName') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">Email</label>
                        <input type="email" wire:model="contactEmail"
                            class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp">
                        @error('contactEmail') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">Mensaje</label>
                        <textarea wire:model="contactMessageText" rows="4"
                            class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp"></textarea>
                        @error('contactMessageText') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit"
                        class="w-full font-mono text-sm px-6 py-3.5 rounded-sm bg-stamp text-ink font-bold hover:-translate-y-0.5 hover:bg-emerald-500 transition"
                        wire:loading.attr="disabled" wire:target="sendMessage">
                        <span wire:loading.remove wire:target="sendMessage">Enviar mensaje →</span>
                        <span wire:loading wire:target="sendMessage">Enviando...</span>
                    </button>
                </form>
                @endif

                <div class="flex gap-3 justify-center flex-wrap mt-6">
                    @if ($profile->github_url)
                    <a href="{{ $profile->github_url }}" target="_blank" rel="noopener" class="font-mono text-xs px-4 py-2.5 rounded-sm border border-line hover:border-stamp hover:text-stamp transition">
                        GitHub
                    </a>
                    @endif
                    @if ($profile->linkedin_url)
                    <a href="{{ $profile->linkedin_url }}" target="_blank" rel="noopener" class="font-mono text-xs px-4 py-2.5 rounded-sm border border-line hover:border-stamp hover:text-stamp transition">
                        LinkedIn
                    </a>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>