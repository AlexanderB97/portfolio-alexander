<?php

use function Livewire\Volt\{state, layout};

layout('components.layouts.app');

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
                <div class="font-mono text-sm">alexander<span class="text-stamp">.dev</span></div>
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
            <div class="inline-flex items-center gap-2 font-mono text-xs uppercase tracking-wide text-stamp border border-stamp-dim rounded-sm px-3 py-1.5 mb-7">
                <span class="text-[8px]">●</span> Disponible para proyectos
            </div>

            <h1 class="font-display font-bold text-5xl md:text-7xl leading-[1.05] tracking-tight max-w-3xl">
                Construyo sistemas que <span class="text-stamp">administran</span> negocios reales.
            </h1>

            <p class="mt-6 text-lg text-paper-dim max-w-xl">
                Desarrollador full stack especializado en Laravel + Livewire. De la primera migración a producción estable: roles, datos, deploys — el trabajo que sostiene un sistema en uso.
            </p>

            <div class="flex gap-4 mt-10 flex-wrap">
                <a href="#proyectos" class="font-mono text-sm px-6 py-3.5 rounded-sm bg-stamp text-ink font-bold hover:-translate-y-0.5 hover:bg-emerald-500 transition">
                    Ver fichas de proyecto →
                </a>
                <a href="#contacto" class="font-mono text-sm px-6 py-3.5 rounded-sm border border-line hover:border-stamp hover:text-stamp hover:-translate-y-0.5 transition">
                    Hablemos
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 border-t border-b border-line mt-20">
                <div class="p-6 border-r border-line">
                    <div class="font-display font-bold text-2xl">3</div>
                    <div class="font-mono text-[11px] uppercase text-paper-dim mt-1">Sistemas en producción</div>
                </div>
                <div class="p-6 border-r border-line">
                    <div class="font-display font-bold text-2xl">4</div>
                    <div class="font-mono text-[11px] uppercase text-paper-dim mt-1">Equipos integrados</div>
                </div>
                <div class="p-6 border-r border-line">
                    <div class="font-display font-bold text-2xl">12+</div>
                    <div class="font-mono text-[11px] uppercase text-paper-dim mt-1">Bugs de prod resueltos</div>
                </div>
                <div class="p-6">
                    <div class="font-display font-bold text-2xl">01</div>
                    <div class="font-mono text-[11px] uppercase text-paper-dim mt-1">TIF en curso</div>
                </div>
            </div>
        </header>

    </div>
</div>