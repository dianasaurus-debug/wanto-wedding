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
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }
    public function adatcategory()
    {
        return $this->belongsTo(KategoriAdat::class, 'adatcategory_id', 'id');
    }
    public function media()
    {
        return $this->hasMany(MediaPost::class, 'post_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
