<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

class Ad extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'price',
        'user_id',
        'category_id',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function mainImage()
    {
        return $this->morphOne(Image::class, 'imageable')->oldestOfMany('created_at', 'min');
    }

    public function scopeActiveAds($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeRejectedAds($query)
    {
        return $query->where('status', 'rejected');
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucfirst($value),

            set: fn(string $value) => strtolower($value)
        );
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucfirst($value),

            set: fn(string $value) => strtolower($value)
        );
    }

    protected function formattedCreatedAt(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->created_at)->format('d M Y, h:i A')
        );
    }

    protected function formattedUpdatedAt(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->updated_at)->format('d M Y, h:i A')
        );
    }

    public function scopeUserAds($query, $userId): mixed
    {
        return $query->where('user_id', $userId);
    }

    protected static function booted()
    {
        static::deleting(function ($event) {
            // Delete related images
            foreach ($event->images as $image) {
                // Delete image file from storage
                if (!empty($image->path) && Storage::disk('public')->exists($image->path)) {
                    Storage::disk('public')->delete($image->path);
                }
                // Delete image record
                $image->delete();
            }
        });
    }
}
