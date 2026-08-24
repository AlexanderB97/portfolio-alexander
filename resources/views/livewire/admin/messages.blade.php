<?php

use function Livewire\Volt\{layout, state, with};
use App\Models\ContactMessage;

layout('components.layouts.admin');

with(['messages' => fn () => ContactMessage::orderByDesc('created_at')->get()]);

$markRead = function ($id) {
    ContactMessage::findOrFail($id)->update(['read' => true]);
};

$delete = function ($id) {
    ContactMessage::findOrFail($id)->delete();
};

?>

<div class="max-w-3xl">
    <div class="flex items-center gap-4 mb-8">
        <a href="/admin" wire:navigate class="font-mono text-xs text-paper-dim hover:text-stamp transition">← Volver</a>
    </div>

    <h1 class="font-display font-semibold text-2xl mb-8">Mensajes de contacto</h1>

    <div class="space-y-3">
        @forelse ($messages as $msg)
            <div class="bg-surface border border-line rounded p-5 {{ !$msg->read ? 'border-l-4 border-l-stamp' : '' }}">
                <div class="flex justify-between items-start gap-4 mb-2">
                    <div>
                        <div class="font-display font-semibold">{{ $msg->name }}</div>
                        <div class="font-mono text-xs text-paper-dim">{{ $msg->email }} — {{ $msg->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="flex gap-2 flex-shrink-0">
                        @unless ($msg->read)
                            <button wire:click="markRead({{ $msg->id }})"
                                    class="font-mono text-[11px] px-2.5 py-1 border border-line rounded-sm hover:border-stamp hover:text-stamp transition">
                                Marcar leído
                            </button>
                        @endunless
                        <button wire:click="delete({{ $msg->id }})"
                                onclick="return confirm('¿Borrar este mensaje?')"
                                class="font-mono text-[11px] px-2.5 py-1 border border-red-900 text-red-400 rounded-sm hover:bg-red-950/30 transition">
                            Borrar
                        </button>
                    </div>
                </div>
                <p class="text-sm text-paper-dim">{{ $msg->message }}</p>
            </div>
        @empty
            <p class="font-mono text-sm text-paper-dim">Todavía no llegaron mensajes.</p>
        @endforelse
    </div>
</div>