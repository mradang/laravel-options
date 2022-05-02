<?php

namespace mradang\LaravelOptions\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['key', 'value'];

    protected $casts = [
        'key' => 'string',
        'value' => 'array',
    ];
}
