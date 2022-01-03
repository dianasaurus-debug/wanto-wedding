<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Booking;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JasaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) //Nampilin semua data tanpa terkecuali di tabel product
    {
        try {
            $all_jasa = Product::with('category')
                ->with('reviews')
                ->get()
                ->groupBy('category_id'); //Proses mendapatkan data dari tabel product
            $data = array(
                'status' => 'success',
                'message' => 'Berhasil menampilkan data vendor',
                'data' => $all_jasa,
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id) // $id = 2
    {
        try {
            $jasa = Product::where('id', $id)
                ->with('reviews')
                ->with('media') //dengan galerinya juga..
                ->first();
            $jasa->update(['jumlah_dilihat'=>$jasa->jumlah_dilihat+1]);
            $product_resource = '';
            if ($jasa)
                $product_resource = new ProductResource($jasa);
            $data = array(
                'status' => 'success',
                'message' => 'Berhasil menampilkan data vendor',
                'data' => $product_resource,
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function getThumbnails(Request $request){
        try {
            $best_seller = Product::withCount('booking')
                            ->orderByDesc('booking_count')
                            ->with('reviews')
                            ->with('category')
                            ->first();
            $cheapest_harga = Product::where('harga','>=',0)->min('harga');
            $cheapest = Product::where('harga', $cheapest_harga)
                        ->with('reviews')
                        ->with('category')
                        ->first();
            $newest = Product::latest()
                        ->with('category')
                        ->with('reviews')
                        ->first();

            $best_seller->kind = 'Best Seller';
            $cheapest->kind = 'Harga Terjangkau';
            $newest->kind = 'Terbaru';

            $thumbnails = Array($best_seller, $cheapest, $newest);

            $data = array(
                'status' => 'success',
                'message' => 'Berhasil menampilkan data thumbnails',
                'data' => $thumbnails,
            );
            return response()->json($data);
        } catch (\Exception $exception){
            $data = array(
                'status' => 'false',
                'message' => $exception->getMessage(),
            );
            return response()->json($data);
        }
    }

    public function getPaketLengkap(Request $request){
        try {
            $paket_lengkap = Product::where('category_id', 1)
                                ->with('reviews')
                                ->with('category')->get();

            $data = array(
                'status' => 'success',
                'message' => 'Berhasil menampilkan data paket lengkap',
                'data' => $paket_lengkap,
            );
            return response()->json($data);
        } catch (\Exception $exception){
            $data = array(
                'status' => 'false',
                'message' => $exception->getMessage(),
            );
            return response()->json($data);
        }
    }

    public function add_review(Request $request){
        try {
            $booking = Booking::where('id', $request->booking_id)->first();
            $product = Product::where('id', $request->product_id)->first();
            $review = Review::create([
                'customer_id' => auth()->user()->id,
                'product_id' => $request->product_id,
                'score' => $request->score,
                'comment' => $request->comment,
                'booking_id' => $request->booking_id
            ]);
            $mean_review = Review::where('product_id', $request->product_id)->avg('score');
            $booking->update(['status'=>8]);
            $product->update(['rating_mean' => $mean_review]);
            $data = array(
                'status' => 'success',
                'message' => 'Berhasil manambahkan review',
                'data' => $review,
            );
            return response()->json($data);
        } catch (\Exception $exception){
            $data = array(
                'status' => 'false',
                'message' => $exception->getMessage(),
            );
            return response()->json($data);
        }
    }
    public function getPencarianPopuler(Request $request){
        try {
            $populer = Product::orderBy('jumlah_dilihat', 'desc')->take(3)->get();
            $data = array(
                'status' => 'success',
                'message' => 'Berhasil menampilkan data pencarian populer',
                'data' => $populer,
            );
            return response()->json($data);
        } catch (\Exception $exception){
            $data = array(
                'status' => 'false',
                'message' => $exception->getMessage(),
            );
            return response()->json($data);
        }
    }
    public function getPencarianTerpopuler(Request $request){
        try {
            $data_total = [];
            $populer = Product::with('reviews')->get();
            foreach ($populer as $p){
                $p->total_review = $p->rating_mean + count($p->reviews);
                array_push($data_total, $p);
            }
            // Desc sort
            usort($data_total,function($first,$second){
                return $first->total_review < $second->total_review;
            });

            $data = array(
                'status' => 'success',
                'message' => 'Berhasil menampilkan data pencarian terpopuler',
                'data' => $data_total,
            );
            return response()->json($data);
        } catch (\Exception $exception){
            $data = array(
                'status' => 'false',
                'message' => $exception->getMessage(),
            );
            return response()->json($data);
        }
    }
    public function getRecommendations(Request $request){
        try {
            $data_users = array();
            $users = User::where('is_admin', 0)->with('reviews')->get();
            foreach ($users as $u){
                $array_ratings = array();
                if(count($u->reviews)>0)
                    foreach ($u->reviews as $review){
                        $array_ratings[$review->product_id] = $review->score;
                    }
                $data_users[$u->id] = $array_ratings;
            }
            $books =  array(

                "phil" => array("my girl" => 2.5, "the god delusion" => 3.5,
                    "tweak" => 3, "the shack" => 4,
                    "the birds in my life" => 2.5,
                    "new moon" => 3.5),

                "sameer" => array("the last lecture" => 2.5, "the god delusion" => 3.5,
                    "the noble wilds" => 3, "the shack" => 3.5,
                    "the birds in my life" => 2.5, "new moon" => 1),

                "john" => array("a thousand splendid suns" => 5, "the secret" => 3.5,
                    "tweak" => 1),

                "peter" => array("chaos" => 5, "php in action" => 3.5),

                "jill" => array("the last lecture" => 1.5, "the secret" => 2.5,
                    "the noble wilds" => 4, "the host: a novel" => 3.5,
                    "the world without end" => 2.5, "new moon" => 3.5),

                "bruce" => array("the last lecture" => 3, "the hollow" => 1.5,
                    "the noble wilds" => 3, "the shack" => 3.5,
                    "the appeal" => 2, "new moon" => 3),

                "tom" => array("chaos" => 2.5)


            );

            // Desc sort

            $data = array(
                'status' => 'success',
                'message' => 'Berhasil menampilkan data rekomendasi',
                'data' => get_recommendations($books, "phil"),
            );
            return response()->json($data);
        } catch (\Exception $exception){
            $data = array(
                'status' => 'false',
                'message' => $exception->getMessage(),
            );
            return response()->json($data);
        }
    }


}
