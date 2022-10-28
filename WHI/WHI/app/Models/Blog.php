<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'section_id',
        'title',
        'text',
    ];

    /**
     * ユーザーとプロフィールのブログとのデータの関係
     */
    public function user(): object
    {
        return $this->belongsTo(User::class);
    }

    /**
     * プロフィールのセクションとブログとのデータの関係
     */
    public function section(): object
    {
        return $this->belongsTo(Section::class);
    }
}
