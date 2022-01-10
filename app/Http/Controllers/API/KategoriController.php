<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\TemaKatalog;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function indexTema(Request $request)
    {
        try {
            $all_tema = TemaKatalog::all(); //Proses mendapatkan data dari tabel product
            $data = array(
                'status' => 'success',
                'message' => 'Berhasil menampilkan data vendor',
                'data' => $all_tema,
            );
            return response()->json($data);
        } catch (\Exception $exception) {
            $data = array(
                [
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan : '.$exception->getMessage()
                ]
            );
            return response()->json($data);
        }
    }
    public function indexKategori(Request $request)
    {
        try {
            $all_categories = ProductCategory::all(); //Proses mendapatkan data dari tabel product
            $data = array(
                'status' => 'success',
                'message' => 'Berhasil menampilkan data vendor',
                'data' => $all_categories,
            );
            return response()->json($data);
        } catch (\Exception $exception) {
            $data = array(
                [
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan : '.$exception->getMessage()
                ]
            );
            return response()->json($data);
        }
    }
}
