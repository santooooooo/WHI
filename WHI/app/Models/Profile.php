<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'icon',
        'career',
        'title',
        'text',
        'mail',
        'twitter',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    ];

<<<<<<< HEAD
<<<<<<<< HEAD:app/Models/Profile.php
    public function user(): object
    {
        return $this->belongsTo(User::class, 'user_id');
========
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
>>>>>>>> 9f31d8ffd8cfc187ad9f6fe11517da0668da0f0a:WHI/app/Models/User.php
=======
    public function user(): object
    {
        return $this->belongsTo(User::class, 'user_id');
>>>>>>> 9f31d8ffd8cfc187ad9f6fe11517da0668da0f0a
    }
}
