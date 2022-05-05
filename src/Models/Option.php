<?php

namespace mradang\LaravelOptions\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['key', 'value'];

    protected function value(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value ? unserialize($value) : null,
            set: fn ($value) => serialize($value),
        );
    }
}
