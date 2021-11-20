<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwal = Schedule::with('booking.product')
            ->with('booking.user')
            ->get();
        return Inertia::render('Jadwal/Index', ['jadwal' => $jadwal]);
    }

}
