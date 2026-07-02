<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Category extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'categories';
    protected $guarded = [];
    protected $casts= [
        'images'=>'string'
    ];
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
    public function getImageUrlAttribute()
    {
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }
        return asset('images/default-avatar.png');
    }
}
