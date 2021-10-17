<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id', 'id');
    }
    public function media()
    {
        return $this->hasMany(MediaPost::class, 'post_id', 'id');
    }
}
