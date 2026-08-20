<?php

use function Livewire\Volt\{state, mount, uses, layout};
use Livewire\WithFileUploads;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

layout('components.layouts.admin');
uses([WithFileUploads::class]);

state([
    'project' => null,
    'ficha_number' => '',
    'category' => '',
    'title' => '',
    'image' => null,
    'currentImage' => null,
    'status_label' => 'Presentado ✓',
    'status_color' => 'stamp',
    'description' => '',
    'role_label' => 'Mi rol',
    'role_description' => '',
    'meta_text' => '',
    'tags_text' => '',
    'repo_url' => '',
    'demo_url' => '',
    'order' => 0,
    'published' => true,
]);

mount(function (?Project $project = null) {
    if ($project) {
        $this->project = $project;
        $this->ficha_number = $project->ficha_number;
        $this->category = $project->category;
        $this->title = $project->title;
        $this->currentImage = $project->image_path;
        $this->status_label = $project->status_label;
        $this->status_color = $project->status_color;
        $this->description = $project->description;
        $this->role_label = $project->role_label;
        $this->role_description = $project->role_description;
        $this->meta_text = collect($project->meta)
            ->map(fn ($row) => "{$row['label']}: {$row['value']}")
            ->implode("\n");
        $this->tags_text = implode(', ', $project->tags);
        $this->repo_url = $project->repo_url;
        $this->demo_url = $project->demo_url;
        $this->order = $project->order;
        $this->published = $project->published;
    } else {
        // sugerir el próximo número de ficha automáticamente
        $this->ficha_number = (Project::max('ficha_number') ?? 0) + 1;
        $this->order = (Project::max('order') ?? 0) + 1;
    }
});

$save = function () {
    $this->validate([
        'ficha_number' => 'required|integer|min:1',
        'category' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'status_label' => 'required|string|max:255',
        'status_color' => 'required|in:stamp,ticket',
        'description' => 'required|string',
        'role_label' => 'required|string|max:255',
        'role_description' => 'required|string',
        'repo_url' => 'nullable|url',
        'demo_url' => 'nullable|url',
        'order' => 'required|integer',
        'image' => 'nullable|image|max:4096',
    ]);

    // parsea "Label: Valor" por línea
    $meta = collect(explode("\n", $this->meta_text))
        ->map(fn ($line) => trim($line))
        ->filter()
        ->map(function ($line) {
            [$label, $value] = array_pad(explode(':', $line, 2), 2, '');
            return ['label' => trim($label), 'value' => trim($value)];
        })
        ->values()
        ->all();

    $tags = collect(explode(',', $this->tags_text))
        ->map(fn ($t) => trim($t))
        ->filter()
        ->values()
        ->all();

    $data = [
        'ficha_number' => $this->ficha_number,
        'category' => $this->category,
        'title' => $this->title,
        'status_label' => $this->status_label,
        'status_color' => $this->status_color,
        'description' => $this->description,
        'role_label' => $this->role_label,
        'role_description' => $this->role_description,
        'meta' => $meta,
        'tags' => $tags,
        'repo_url' => $this->repo_url ?: null,
        'demo_url' => $this->demo_url ?: null,
        'order' => $this->order,
        'published' => $this->published,
    ];

    if ($this->image) {
        if ($this->project && $this->project->image_path) {
            Storage::disk('public')->delete($this->project->image_path);
        }
        $data['image_path'] = $this->image->store('projects', 'public');
    }

    if ($this->project) {
        $this->project->update($data);
    } else {
        Project::create($data);
    }

    $this->redirect('/admin', navigate: true);
};

?>

<div class="max-w-2xl">
    <div class="flex items-center gap-4 mb-8">
        <a href="/admin" wire:navigate class="font-mono text-xs text-paper-dim hover:text-stamp transition">← Volver</a>
    </div>

    <h1 class="font-display font-semibold text-2xl mb-8">
        {{ $project ? 'Editar proyecto' : 'Nuevo proyecto' }}
    </h1>

    <form wire:submit="save" class="space-y-5">

        <div>
            <label class="font-mono text-[11px] uppercase text-paper-dim block mb-2">Imagen del proyecto</label>
            @if ($image)
                <img src="{{ $image->temporaryUrl() }}" class="w-full h-40 object-cover rounded-sm border-2 border-stamp mb-2">
            @elseif ($currentImage)
                <img src="{{ Storage::url($currentImage) }}" class="w-full h-40 object-cover rounded-sm border border-line mb-2">
            @else
                <div class="w-full h-40 rounded-sm bg-surface-2 border border-dashed border-line flex items-center justify-center font-mono text-xs text-paper-dim mb-2">
                    sin imagen
                </div>
            @endif
            <input type="file" wire:model="image" accept="image/*"
                   class="font-mono text-xs text-paper-dim file:mr-3 file:py-2 file:px-3 file:rounded-sm file:border file:border-line file:bg-surface file:text-paper file:font-mono file:text-xs hover:file:border-stamp">
            <div wire:loading wire:target="image" class="font-mono text-[11px] text-stamp mt-1.5">Subiendo...</div>
            @error('image') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">N° ficha</label>
                <input type="number" wire:model="ficha_number" class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp">
                @error('ficha_number') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="col-span-2">
                <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">Categoría</label>
                <input type="text" wire:model="category" placeholder="SISTEMA DE GESTIÓN" class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp">
                @error('category') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">Título</label>
            <input type="text" wire:model="title" class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp">
            @error('title') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">Etiqueta de estado</label>
                <input type="text" wire:model="status_label" placeholder="Presentado ✓" class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp">
            </div>
            <div>
                <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">Color del sello</label>
                <select wire:model="status_color" class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp">
                    <option value="stamp">Verde (stamp)</option>
                    <option value="ticket">Ámbar (ticket)</option>
                </select>
            </div>
        </div>

        <div>
            <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">Descripción</label>
            <textarea wire:model="description" rows="3" class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp"></textarea>
            @error('description') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">Etiqueta del rol</label>
                <input type="text" wire:model="role_label" placeholder="Mi rol / Enfoque" class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp">
            </div>
            <div class="col-span-2">
                <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">Descripción del rol</label>
                <textarea wire:model="role_description" rows="3" class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp"></textarea>
            </div>
        </div>

        <div>
            <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">
                Metadatos (uno por línea, formato "Etiqueta: Valor")
            </label>
            <textarea wire:model="meta_text" rows="3" placeholder="Equipo: 4 personas&#10;Rol: Backend / Auth lead&#10;Estado: Producción" class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm font-mono focus:outline-none focus:border-stamp"></textarea>
        </div>

        <div>
            <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">Tags (separados por coma)</label>
            <input type="text" wire:model="tags_text" placeholder="Laravel 12, Livewire 3, Tailwind" class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">URL del repositorio</label>
                <input type="text" wire:model="repo_url" placeholder="https://github.com/..." class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp">
                @error('repo_url') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">URL de la demo</label>
                <input type="text" wire:model="demo_url" placeholder="https://..." class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp">
                @error('demo_url') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 items-end">
            <div>
                <label class="font-mono text-[11px] uppercase text-paper-dim block mb-1.5">Orden</label>
                <input type="number" wire:model="order" class="w-full bg-surface border border-line rounded-sm px-3 py-2.5 text-sm focus:outline-none focus:border-stamp">
            </div>
            <label class="flex items-center gap-2 font-mono text-xs text-paper-dim pb-2.5">
                <input type="checkbox" wire:model="published" class="accent-stamp">
                Publicado (visible en la página)
            </label>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="font-mono text-sm px-6 py-3 rounded-sm bg-stamp text-ink font-bold hover:bg-emerald-500 transition">
                Guardar
            </button>
            <a href="/admin" wire:navigate class="font-mono text-sm px-6 py-3 rounded-sm border border-line hover:border-stamp hover:text-stamp transition">
                Cancelar
            </a>
        </div>
    </form>
</div>