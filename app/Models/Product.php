<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'products';
    protected $guarded = [];
    protected $casts = [
        'images' => 'array',
    ];

    protected $appends = ['image_url'];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function getFinalPriceAttribute(): int
    {
        if ($this->discount){
            return $this->price * (1-($this->discount *0.01));
        }
        return $this->price;
    }

    public function getImageUrlAttribute(): string
    {
        // اگر images آرایه باشه و خالی نباشه
        if (!empty($this->images) && is_array($this->images)) {
            $firstImage = $this->images[0] ?? null;

            if ($firstImage) {
                // اگر تصویر در storage هست
                if (Storage::disk('public')->exists($firstImage)) {
                    return Storage::disk('public')->url($firstImage);
                }

                // اگر تصویر در public path هست
                if (file_exists(public_path($firstImage))) {
                    return asset($firstImage);
                }

                // اگر URL کامل هست
                if (filter_var($firstImage, FILTER_VALIDATE_URL)) {
                    return $firstImage;
                }
            }
        }

        // تصویر پیش‌فرض
        return asset('images/default-product.png');
    }

    /**
     * Get all images URLs
     */
    public function getImagesUrlAttribute(): array
    {
        if (empty($this->images) || !is_array($this->images)) {
            return [asset('images/default-product.png')];
        }

        return collect($this->images)->map(function ($image) {
            if (!$image) return null;

            // اگر URL کامل هست
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                return $image;
            }

            // اگر در storage هست
            if (Storage::disk('public')->exists($image)) {
                return Storage::disk('public')->url($image);
            }

            // اگر در public path هست
            if (file_exists(public_path($image))) {
                return asset($image);
            }

            return null;
        })->filter()->values()->toArray();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }
        return $query;
    }
    public function scopeByCategory($query, $categoryId)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }
    public function scopeByPriceRange($query, $minPrice, $maxPrice)
    {
        if ($minPrice !== null && $maxPrice !== null) {
            return $query->whereBetween('price', [$minPrice, $maxPrice]);
        }
        return $query;
    }
    public function scopeInStock($query)
    {
        return $query->where('total', '>', 0);
    }
    public function scopeSortBy($query, $sort)
    {
        return match ($sort) {
            'newest' => $query->latest(),
            'oldest' => $query->oldest(),
            'cheapest' => $query->orderBy('price', 'asc'),
            'expensive' => $query->orderBy('price', 'desc'),
            'bestselling' => $query->orderBy('sold_count', 'desc'),
            'most_viewed' => $query->orderBy('views', 'desc'),
            'discounted' => $query->whereNotNull('discount')
                ->orderBy('discount', 'desc'),
            default => $query->latest(),
        };
    }
}
