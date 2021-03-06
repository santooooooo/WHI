<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * ユーザーとプロフィールとのデータの関係
     */
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    /**
     * ユーザーとプロフィールのセクションとのデータの関係
     */
    public function sections(): object
    {
        return $this->hasMany(Section::class, 'user_id');
    }

    /**
     * ユーザーとプロフィールのセクションとのデータの関係
     */
    public function contents(): object
    {
        return $this->hasMany(Content::class, 'user_id');
    }

    /**
     * ユーザーとプロフィールのブログとのデータの関係
     */
    public function blogs(): object
    {
        return $this->hasMany(Blog::class, 'user_id');
    }
}
