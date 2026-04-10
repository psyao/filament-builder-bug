<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title',
        'body',
    ];

    protected function casts(): array
    {
        return [
            'body' => 'array',
        ];
    }
}
