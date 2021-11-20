<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function daftar_booking(Request $request){
        try {
            $booking = Booking::where('customer_id', auth()->user()->id)->get();
            $data = array(
                'success' => true,
                'message' => 'Berhasil menampilkan data booking',
                'data' => $booking,
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

    public function make_booking(Request $request){
        $validator = Validator::make($request->all(), [
            'start_booking' => 'required',
            'end_booking' => 'required',
            'product_id' => 'required',
            'bank_account_id' => 'required'
        ]);

        if ($validator->fails()) {
            $data = array(
                'success' => false,
                'message' => 'Mohon masukkan data dengan lengkap',
            );
            return response()->json($data, 400);
        } else {
            try {

                $time_from = $request->start_booking;
                $time_to = $request->end_booking;
                $jadwal_not_available = Schedule::where(function ($q) use ($time_from, $time_to) {
                    $q->where(function (Builder $query) use ($time_from) {
                        $query->where('start_booking', '<=', $time_from)
                            ->where('end_booking', '>=', $time_from);
                    })->orWhere(function (Builder $query) use ($time_to) {
                            $query->where('start_booking', '<=', $time_to)
                                ->where('end_booking', '>=', $time_to);
                    })->where('status', 0);
                })->get();
                if(count($jadwal_not_available)==0){
                    $booking = Booking::create([
                        'start_booking' => $request->start_booking,
                        'end_booking' => $request->end_booking,
                        'product_id' => $request->product_id,
                        'customer_id' => auth()->user()->id,
                    ]);
                    $product = Product::findOrFail($request->product_id);
                    $jadwal = Schedule::create([
                        'booking_id' => $booking->id,
                        'start_booking' => $request->start_booking,
                        'end_booking' => $request->end_booking,
                        'status' => 1
                    ]);
                    $payment_dp = Payment::create([
                        'nominal' => $product->nominal_dp,
                        'jenis_pembayaran' => 0, //jenis dp
                        'status_pembayaran' => 0, //belum dikonfirmasi
                        'bank_account_id' => $request->bank_account_id
                    ]);
                    $booking->update(['payment_id' => $payment_dp->id]);
                    $data = array(
                        'success' => true,
                        'message' => 'Berhasil menambahkan data booking',
                        'data' => $booking,
                    );
                    return response()->json($data,200);
                } else {
                    $data = array(
                        'success' => true,
                        'message' => 'Gagal menambahkan data booking. Jadwal sudah dipakai',
                    );
                    return response()->json($data,200);
                }


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
}
