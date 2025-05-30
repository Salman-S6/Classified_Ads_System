<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        "name",
        'slug',
    ];

    public function ads(): HasMany
    {
        return $this->hasMany(Ad::class);
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucfirst($value),

            set: fn(string $value) => strtolower($value)
        );
    }

    protected function slug(): Attribute
    {
        return Attribute::make(
            set: fn($value, $attributes) =>
            Str::slug($attributes['name'] ?? $value)
        );
    }
}
