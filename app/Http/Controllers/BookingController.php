<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models;
use Inertia\Inertia;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $allbookings = Booking::with('user')
            ->with('product')
            ->with('payment')
            ->latest()
            ->paginate(5);
        return Inertia::render('Booking/Index', ['allbookings' => $allbookings]);
    }
}
