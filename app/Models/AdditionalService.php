<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalService extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bookings()
    {
        return $this->belongsToMany(
            Booking::class,
            'bookings_services',
            'booking_id',
            'additional_service_id');
    }
}
