<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\DocBlock\Tags\Generic;

class BookingController extends Controller
{
    public function daftar_booking(Request $request){
        try {
            $booking = Booking::where('customer_id', auth()->user()->id)
            ->with('payment.bank_account')
            ->with('product', 'product.category', 'product.reviews')
            ->with('review')
            ->get();
            foreach ($booking as $b){
                if($b->status==4){
                    if(Carbon::now()>Carbon::parse($b->end_booking)){
                        $b->update(['status' => 7]);
                    } else if(Carbon::now()<=Carbon::parse($b->end_booking)&&Carbon::now()>=Carbon::parse($b->start_booking)){
                        $b->update(['status' => 6]);
                    }
                }
            }
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

    public function detail_booking(Request $request, $id){
        try {
            $booking = Booking::where('customer_id', auth()->user()->id)
            ->where('id', $id)
            ->with('payment.bank_account')
            ->with('product', 'product.category', 'product.reviews')
            ->with('review')
            ->first();
            if($booking->status==4){
                if(Carbon::now()>Carbon::parse($booking->end_booking)){
                    $booking->update(['status' => 7]);
                } else if(Carbon::now()<=Carbon::parse($booking->end_booking)&&Carbon::now()>=Carbon::parse($booking->start_booking)){
                    $booking->update(['status' => 6]);
                }
            }
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
            'end_booking' => 'required|after:start_booking',
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
            $isNextMonth = Carbon::parse($request->start_booking)>=Carbon::now()->addMonth();
            if($isNextMonth==true){
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
                        });
                    })->where('status', '!=', 2)->get();
                    if(count($jadwal_not_available)==0){
                        $booking = Booking::create([
                            'start_booking' => $request->start_booking,
                            'end_booking' => $request->end_booking,
                            'product_id' => $request->product_id,
                            'customer_id' => auth()->user()->id,
                            'status' => 0
                        ]);
                        $product = Product::findOrFail($request->product_id);
                        $jadwal = Schedule::create([
                            'booking_id' => $booking->id,
                            'start_booking' => $request->start_booking,
                            'end_booking' => $request->end_booking,
                            'status' => 2
                        ]);
                        $nominal_dp = generate_unique_price($product->nominal_dp);
                        $payment_dp = Payment::create([
                            'nominal' => $nominal_dp["hasil"],
                            'kode_unik' => $nominal_dp["kode_unik"],
                            'jenis_pembayaran' => 2, //jenis dp
                            'status_pembayaran' => 2, //belum dikonfirmasi
                            'bank_account_id' => $request->bank_account_id,
                            'booking_id' => $booking->id
                        ]);
                        $data_booking = Booking::where('id', $booking->id)
                            ->with('payment.bank_account')
                            ->with('product.category')
                            ->with('review')
                            ->first();
                        $data = array(
                            'success' => true,
                            'message' => 'Berhasil menambahkan data booking',
                            'data' => $data_booking,
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
            } else {
                $data = array(
                    [
                        'success' => false,
                        'message' => 'Tanggal booking harus satu bulan setelah hari ini'
                    ]
                );
                return response()->json($data, 400);
            }

        }
    }

    public function cancel_booking(Request $request, $id){
        try {
            $booking = Booking::where('customer_id', auth()->user()->id)
            ->where('id', $id)
            ->first();

            $payment = Payment::where('booking_id', $booking->id)->first();

            $booking->update(['status'=>5]);
            $payment->update(['status_pembayaran'=>3]);//dibatalkan
            $data = array(
                'success' => true,
                'message' => 'Berhasil membatalkan data booking',
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
}
