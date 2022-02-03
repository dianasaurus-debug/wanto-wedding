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
    public function index(Request $request)
    {
        try {
            $new_jasa = array();
            $all_jasa = Product::with('category')
                ->with('reviews')
                ->with(["booking" => function($q){
                    $q->whereNotIn('status', [7,8])
                    ->where('customer_id', auth()->id());
                }])
                ->get()
                ->groupBy('category_id'); //Proses mendapatkan data dari tabel product
            foreach ($all_jasa as $key=>$value){
                foreach ($value as $jasa){
                    if(count($jasa->booking)>0){
                        $jasa->is_ordered = 1;
                    } else {
                        $jasa->is_ordered = 0;
                    }
                    $new_jasa[$key][]=$jasa;
                }
            }
            $data = array(
                'status' => 'success',
                'message' => 'Berhasil menampilkan data vendor',
                'data' => $new_jasa,
            );
            return response()->json($data);
        } catch (\Exception $exception) {
            $data = array(
                [
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan : ' . $exception->getMessage()
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id) // $id = 2
    {
        try {

            $jasa = Product::where('id', $id)
                ->with('reviews')
                ->with(["booking" => function($q){
                    $q->whereNotIn('status', [7,8])
                        ->where('customer_id', auth()->id());
                }])
                ->with('media') //dengan galerinya juga..
                ->first();
            $jasa->update(['jumlah_dilihat' => $jasa->jumlah_dilihat + 1]);
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
                    'message' => 'Terjadi kesalahan : ' . $exception->getMessage()
                ]
            );
            return response()->json($data);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function getThumbnails(Request $request)
    {
        try {
            $best_seller = Product::withCount('booking')
                ->orderByDesc('booking_count')
                ->with('reviews')
                ->with('category')
                ->first();
            $cheapest_harga = Product::where('harga', '>=', 0)->min('harga');
            $cheapest = Product::where('harga', $cheapest_harga)
                ->with('reviews')
                ->with('category')
                ->first();
            $newest = Product::latest()
                ->with('category')
                ->with('reviews')
                ->first();

            if ($best_seller && $cheapest && $newest) {
                $best_seller->kind = 'Best Seller';
                $cheapest->kind = 'Harga Terjangkau';
                $newest->kind = 'Terbaru';
                $thumbnails = array($best_seller, $cheapest, $newest);
            } else {
                $thumbnails = [];
            }

            $data = array(
                'status' => 'success',
                'message' => 'Berhasil menampilkan data thumbnails',
                'data' => $thumbnails,
            );
            return response()->json($data);
        } catch (\Exception $exception) {
            $data = array(
                'status' => 'false',
                'message' => $exception->getMessage(),
            );
            return response()->json($data);
        }
    }

    public function getPaketLengkap(Request $request)
    {
        try {
            $new_jasa = array();
            $paket_lengkap = Product::where('category_id', 1)
                ->with('reviews')
                ->with(["booking" => function($q){
                    $q->whereNotIn('status', [7,8])
                        ->where('customer_id', auth()->id());
                }])
                ->with('category')->get();
            foreach ($paket_lengkap as $jasa){
                if(count($jasa->booking)>0){
                    $jasa->is_ordered = 1;
                } else {
                    $jasa->is_ordered = 0;
                }
                $new_jasa[]=$jasa;
            }
            $data = array(
                'status' => 'success',
                'message' => 'Berhasil menampilkan data paket lengkap',
                'data' => $new_jasa,
            );
            return response()->json($data);
        } catch (\Exception $exception) {
            $data = array(
                'status' => 'false',
                'message' => $exception->getMessage(),
            );
            return response()->json($data);
        }
    }

    public function add_review(Request $request)
    {
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
            $booking->update(['status' => 8]);
            $product->update(['rating_mean' => $mean_review]);
            $data = array(
                'status' => 'success',
                'message' => 'Berhasil manambahkan review',
                'data' => $review,
            );
            return response()->json($data);
        } catch (\Exception $exception) {
            $data = array(
                'status' => 'false',
                'message' => $exception->getMessage(),
            );
            return response()->json($data);
        }
    }

    public function getPencarianPopuler(Request $request)
    {
        try {
            $populer = Product::orderBy('jumlah_dilihat', 'desc')->take(3)->get();
            $data = array(
                'status' => 'success',
                'message' => 'Berhasil menampilkan data pencarian populer',
                'data' => $populer,
            );
            return response()->json($data);
        } catch (\Exception $exception) {
            $data = array(
                'status' => 'false',
                'message' => $exception->getMessage(),
            );
            return response()->json($data);
        }
    }

    public function getPencarianTerpopuler(Request $request)
    {
        try {
            $data_total = [];
            $populer = Product::with('reviews')->get();
            foreach ($populer as $p) {
                $p->total_review = $p->rating_mean + count($p->reviews);
                array_push($data_total, $p);
            }
            // Desc sort
            usort($data_total, function ($first, $second) {
                return $first->total_review < $second->total_review;
            });

            $data = array(
                'status' => 'success',
                'message' => 'Berhasil menampilkan data pencarian terpopuler',
                'data' => $data_total,
            );
            return response()->json($data);
        } catch (\Exception $exception) {
            $data = array(
                'status' => 'false',
                'message' => $exception->getMessage(),
            );
            return response()->json($data);
        }
    }

    public function getRecommendations(Request $request)
    {
        try {
            $data_users = array();
            $users = User::where('is_admin', 0)->with('reviews.product')->get();
            $auth_user = User::where('id', auth()->id())->with('reviews.product')->first();
            $all_products = Product::get();
            $array_of_users_rating = array();
            foreach ($users as $u) { //dapetin data review dari user
                $array_ratings = array();
                if (count($u->reviews) > 0)
                    foreach ($u->reviews as $review) {
                        $array_ratings[$review->product_id] = $review->score;
                    }
                $data_users[$u->id] = $array_ratings;
            }
            $books = array(
                "user1" => array("redmi note7" => 5, "vivo y91" => 4, "iphone 11" => 3),
                "user2" => array("vivo y91" => 3, "iphone 11" => 2, "samsung a20" => 4, "redmi note5" => 1),
                "user3" => array("redmi note7" => 3, "redmi note5" => 3),
                "user4" => array("samsung a10" => 4, "iphone 11" => 1),
                "user5" => array("redmi note7" => 2, "vivo y91" => 2, "iphone 11" => 4, "redmi note5" => 5),
                "user6" => array("samsung a10" => 2, "redmi note7" => 5, "iphone 11" => 4)
            ); //dapet dari jurnal
            $qualified_users = array();
            foreach ($users as $user) { //masukinn rating2 produknya ke masing2 usernya
                $array_of_products = array();
                if (count($user->reviews) > 0) {
                    array_push($qualified_users, $user);
                    foreach ($user->reviews as $review) {
                        $array_of_products[$review->product->id] = $review->score;
                    }
                    $array_of_users_rating[$user->id] = $array_of_products;
                } else {
                    foreach ($all_products as $product) {
                        $array_of_products[$product->id] = $product->rating_mean;
                    }
                    $array_of_users_rating[$user->id] = $array_of_products;
                }
            }
            $id_of_products = array();
            if (count($auth_user->reviews) > 0) { //buat nampilin rankingnya
                $array_of_recommendations = get_recommendations($array_of_users_rating, $auth_user->id);
                arsort($array_of_recommendations);
                $i = 0;
                if (count($array_of_recommendations) > 3) {
                    foreach ($array_of_recommendations as $key => $value) {
                        if ($i <= 2) {
                            array_push($id_of_products, strval($key));
                        }
                        $i++;
                    }
                } else {
                    foreach ($array_of_recommendations as $key => $value) {
                        array_push($id_of_products, strval($key[1]));
                        $i = 0;
                    }
                }
            } else {
                $array_of_recommendations = $array_of_users_rating[$auth_user->id];
                arsort($array_of_recommendations);
                $i = 0;
                if (count($array_of_recommendations) > 3) {
                    foreach ($array_of_recommendations as $key => $value) {
                        if ($i <= 2) {
                            array_push($id_of_products, strval($key));
                        }
                        $i++;
                    }
                } else {
                    foreach ($array_of_recommendations as $key => $value) {
                        array_push($id_of_products, strval($key));
                        $i++;
                    }

                }

            }
            $placeholders = implode(',', array_fill(0, count($id_of_products), '?'));
            $products = Product::whereIn('id', $id_of_products)->orderByRaw("field(id,{$placeholders})", $id_of_products)->with('category')->get();
            $data = array(
                'status' => 'success',
                'message' => 'Berhasil menampilkan data rekomendasi',
                'data' => $products
            );
            return response()->json($data);
        } catch (\Exception $exception) {
            $data = array(
                'status' => 'false',
                'message' => $exception->getMessage(),
            );
            return response()->json($data);
        }
    }

    public function cari_vendor(Request $request)
    {
        try {
            $jasa = Product::where('nama', 'like', '%' . request()->query('cari') . '%')
                ->orWhere('deskripsi', 'like', '%' . request()->query('cari') . '%')
                ->with('category')
                ->with('reviews')
                ->get();
            $data = array(
                'status' => 'success',
                'message' => 'Berhasil menampilkan data vendor',
                'data' => $jasa,
            );
            return response()->json($data);
        } catch (\Exception $exception) {
            $data = array(
                [
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan : ' . $exception->getMessage()
                ]
            );
            return response()->json($data);
        }
    }

    public function index_global(Request $request)
    {
        try {
            $all_jasa = Product::with('category')
                ->with("booking")
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
                    'message' => 'Terjadi kesalahan : ' . $exception->getMessage()
                ]
            );
            return response()->json($data);
        }
    }
    public function getPaketLengkap_global(Request $request)
    {
        try {
            $new_jasa = array();
            $paket_lengkap = Product::where('category_id', 1)
                ->with('reviews')
                ->with("booking")
                ->with('category')->get();
            $data = array(
                'status' => 'success',
                'message' => 'Berhasil menampilkan data paket lengkap',
                'data' => $paket_lengkap,
            );
            return response()->json($data);
        } catch (\Exception $exception) {
            $data = array(
                'status' => 'false',
                'message' => $exception->getMessage(),
            );
            return response()->json($data);
        }
    }

    public function show_global($id) // $id = 2
    {
        try {

            $jasa = Product::where('id', $id)
                ->with('reviews')
                ->with("booking")
                ->with('media') //dengan galerinya juga..
                ->first();
            $jasa->update(['jumlah_dilihat' => $jasa->jumlah_dilihat + 1]);
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
                    'message' => 'Terjadi kesalahan : ' . $exception->getMessage()
                ]
            );
            return response()->json($data);
        }
    }



}
