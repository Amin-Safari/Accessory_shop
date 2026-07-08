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


}
