<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function index()
    {
        $allpayments = Payment::with('bank_account')
            ->with('booking.user')
            ->latest()
            ->paginate(7);
        return Inertia::render('Payment/Index', ['allpayments' => $allpayments]);
    }
}
