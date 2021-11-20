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
        return $this->hasOne(Booking::class, 'payment_id', 'id');
    }
}
