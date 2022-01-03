<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Schedule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class PaymentController extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload_bukti_pembayaran(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bukti_pembayaran' => 'required'
        ]);

        if ($validator->fails()) {
            $data = array(
                'success' => false,
                'message' => 'Mohon masukkan data dengan lengkap',
            );
            return response()->json($data, 400);
        } else {
            try {
                $payment = Payment::where('id', $id)->get();
                if($request->has('bukti_pembayaran')){
                    $path_image = public_path('images/bukti_pembayaran/'.$payment->bukti_pembayaran);
                    if(File::exists($path_image)) {
                        File::delete($path_image);
                    }
                    $destinationPath = public_path('/images/bukti_pembayaran/'); // upload path
                    // Upload Orginal Image
                    $file = $request->file('bukti_pembayaran');
                    $foto_bukti_pembayaran = time() . "." . $file->getClientOriginalExtension();
                    $file->move($destinationPath, $foto_bukti_pembayaran);
                    $payment->update([
                        'bukti_pembayaran' => $foto_bukti_pembayaran,
                    ]);
                    $booking = Booking::where('id', $payment->booking_id)->first();
                    $payment->jenis_pembayaran == 2 ? $booking->update(['status'=>1]) : $booking->update(['status' => 3]);
                }

                $data = array(
                    'success' => true,
                    'message' => 'Berhasil upload bukti pembayaran',
                );
                return response()->json($data, 200);
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
