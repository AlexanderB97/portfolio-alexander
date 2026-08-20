<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'ficha_number',
        'category',
        'title',
        'image_path',
        'status_label',
        'status_color',
        'description',
        'role_label',
        'role_description',
        'meta',
        'tags',
        'repo_url',
        'demo_url',
        'order',
        'published',
    ];

    protected $casts = [
        'meta' => 'array',
        'tags' => 'array',
        'published' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}