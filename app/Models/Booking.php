<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function payment()
    {
        return $this->hasMany(Payment::class, 'booking_id', 'id');
    }
    public function schedule()
    {
        return $this->hasOne(Schedule::class, 'booking_id', 'id');
    }
    public function review()
    {
        return $this->hasOne(Review::class, 'booking_id', 'id');
    }
    public function services()
    {
        return $this->belongsToMany(
            AdditionalService::class,
            'bookings_services',
            'additional_service_id',
            'booking_id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('product', function($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $search . '%');
                })->orWhereHas('user', function($q) use ($search) {
                    $q->where('nama_depan', 'like', '%'.$search.'%')
                        ->orWhere('nama_belakang', 'like', '%'.$search.'%');
                });

            });
        })->when($filters['kategori'] ?? null, function ($query, $kategori) {
            $query->whereHas('product', function($q) use ($kategori) {
                $q->where('category_id', $kategori);
            });
        });
    }
}
