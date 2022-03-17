<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
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

    /**
     * ユーザーとプロフィールのセクションとのデータの関係
     */
    public function user(): object
    {
        return $this->belongsTo(User::class);
    }

    /**
     * プロフィールのセクションとブログのデータの関係
     */
    public function blogs(): object
    {
        return $this->hasMany(Blog::class, 'section_id');
    }
}
