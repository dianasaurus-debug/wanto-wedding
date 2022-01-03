<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function bank_account()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id', 'id');
    }
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('booking.product', function($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%');
                });
            });
        })->when($filters['jenis_pembayaran'] ?? null, function ($query, $jenis_pembayaran) {
            $query->where('jenis_pembayaran', $jenis_pembayaran);
        })->when($filters['status_pembayaran'] ?? null, function ($query, $status_pembayaran) {
                $query->where('status_pembayaran', $status_pembayaran);
        });
    }

}
