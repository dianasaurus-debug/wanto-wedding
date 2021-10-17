<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function booking()
    {
        return $this->hasMany(Booking::class, 'product_id', 'id');
    }
    public function media()
    {
        return $this->hasMany(MediaProduct::class, 'product_id', 'id');
    }
}
