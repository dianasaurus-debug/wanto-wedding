<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function daftar_jadwal(Request $request){
        try {
            $jadwal = Schedule::whereHas('booking', function($q){
                $q->where('customer_id', auth()->user()->id);
            })->with('booking')->get();
            $data = array(
                'success' => true,
                'message' => 'Berhasil menampilkan data booking',
                'data' => $jadwal,
            );
            return response()->json($data,200);
        } catch (\Exception $exception) {
            $data = array(
                [
                    'success' => false,
                    'message' => 'Terjadi kesalahan : '.$exception->getMessage()
                ]
            );
            return response()->json($data, 400);
        }
    }
}
