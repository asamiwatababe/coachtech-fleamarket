<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'profile_image',
        'postal_code',
        'address',
        'building',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    // 購入履歴（購入リレーション）
    public function purchases()
    {
        return $this->belongsToMany(Item::class, 'purchases')->withTimestamps();
    }

    //いいねのリレーション(いいねしたユーザー)多対多の定義
    public function likes()
    {
        return $this->belongsToMany(Item::class, 'likes');
    }
}
