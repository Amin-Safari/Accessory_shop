<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['name', 'phone' , 'address', 'city_id', 'province_id', 'postal_code'])]
#[Hidden(['remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'image' => "string"
//            'email_verified_at' => 'datetime',
//            'password' => 'hashed',
        ];
    }

//    public function province(): BelongsTo
//    {
//        return $this->belongsTo(Province::class);
//    }
//
//    public function city(): BelongsTo
//    {
//        return $this->belongsTo(City::class);
//    }

    /** @use HasFactory<UserFactory> */


}
