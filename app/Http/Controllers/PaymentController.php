<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function index()
    {
        $allpayments = Payment::with('bank_account')
            ->filter(\Illuminate\Support\Facades\Request::only(['search', 'jenis_pembayaran','status_pembayaran']))
            ->with('booking.user', 'booking.product')
            ->latest()
            ->paginate(7);
        return Inertia::render('Payment/Index', [
            'filters' => \Illuminate\Support\Facades\Request::all('search', 'jenis_pembayaran','status_pembayaran'),
            'allpayments' => $allpayments]);
    }

    public function verifikasi(Request $request, $id)
    {
        try {
            $payment = Payment::where('id', $id)->first();
            $booking = Booking::where('id', $payment->booking_id)
                ->with('product')
                ->first();
            if($payment->status_pembayaran!=1){
                $payment->update(['status_pembayaran' => 1]);
                $payment->update(['confirmed_at' => Carbon::now()]);
                if($payment->jenis_pembayaran==2){
                    $booking->update(['status'=>2]);
                    $jadwal = Schedule::where('booking_id', $payment->booking_id)->first();
                    $jadwal->update(['status' => 1]);//diupdate ke sudah masuk jadwal
                    $nominal_pelunasan = generate_unique_price($booking->product->harga-$booking->product->nominal_dp);
                    $payment_pelunasan = Payment::create([
                        'nominal' => $nominal_pelunasan["hasil"],
                        'kode_unik' => $nominal_pelunasan["kode_unik"],
                        'jenis_pembayaran' => 1, //jenis pelunasan
                        'status_pembayaran' => 2, //belum dikonfirmasi
                        'bank_account_id' => $payment->bank_account_id,
                        'booking_id' => $booking->id
                    ]);

                } else {
                    $jadwal = Schedule::where('booking_id', $payment->booking_id)->first();
                    $jadwal->update(['status' => 1]);//diupdate ke sudah masuk jadwal
                    $booking->update(['status'=>4]);
                }
                $data = [
                    'success' => true,
                    'data' => $payment,
                    'message' => 'Data pembayaran berhasil diverifikasi'
                ];
            } else {
                $data = [
                    'success' => false,
                    'data' => $payment,
                    'message' => 'Data sudah pernah diverifikasi'
                ];
            }
            return response()->json($data);
        } catch (\Exception $exception){
            $data = [
                'success' => false,
                'message' => 'Data pembayaran gagal diverifikasi '.$exception->getMessage()
            ];
            return response()->json($data);
        }

    }

}
