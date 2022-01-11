<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwal_terpakai = Schedule::with('booking.product')
            ->whereNotIn('status', [0,1,5])
            ->with('booking.user')
            ->get();
        $jadwal_off = Schedule::where('status', 0)
            ->with('booking.user')
            ->get();
        $data = [
          'jadwal_terpakai' => $jadwal_terpakai,
            'jadwal_off' => $jadwal_off
        ];
        return Inertia::render('Jadwal/Index', ['jadwal' => $data]);
    }

    public function add_tanggal_off(Request $request)
    {
        Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',
        ])->validate();
        try {
            $jadwal = Schedule::create([
                'start_booking' => $request->start_date,
                'end_booking' => $request->end_date,
                'status' => 0
            ]);
            $data = [
                'success' => true,
                'data' => $jadwal,
                'message' => 'Jadwal off berhasil ditambahkan'
            ];
            return response()->json($data);
        } catch (\Exception $e) {
            $data = [
                'success' => false,
                'message' => 'Jadwal off gagal ditambahkan '.$e->getMessage()
            ];
            return response()->json($data);
        }
    }

}
