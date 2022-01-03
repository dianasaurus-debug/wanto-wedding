<?php

namespace App\Http\Controllers;

use App\Models\MediaProduct;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Intervention\Image\Facades\Image;

class JasaController extends Controller
{
//    /**
//     * Display a listing of the resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
    public function index()
    {
        if (request()->query('cari')) {
            $alljasa = Product::where('nama', 'like', '%' . request()->query('cari') . '%')
                ->latest()
                ->with('user')
                ->paginate(7);
        } else {
            $alljasa = Product::latest()
                ->with('user')
                ->paginate(7);
        }
        return Inertia::render('Jasa/Index', ['alljasa' => $alljasa]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $allkategori = ProductCategory::get();
        return Inertia::render('Jasa/Create', ['allkategori' => $allkategori]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'image_posts' => 'required',
            'image_posts.*' => 'mimes:jpeg,jpg,png,gif,csv,txt,pdf|max:2048',
            'nama' => 'required|string',
            'harga' => 'required',
            'deskripsi' => 'required',
            'cover' => 'required',
            'nominal_dp' => 'required',
            'cover.' => 'mimes:jpeg,jpg,png,gif,csv,txt,pdf|max:2048'
        ]);
        try{
            if($request->hasfile('cover')&&$request->hasfile('image_posts')) {
                $file_cover = $request->file('cover');
                $file_ext = $file_cover->getClientOriginalExtension();
                $file_name = 'vendor_cover_'.time().'.'.$file_ext;
                $file_cover->move(public_path().'/images/uploads/', $file_name);

                $product = Product::create([
                    'nama' => $request->nama,
                    'harga' => convert_to_nominal($request->harga),
                    'deskripsi' => $request->deskripsi,
                    'cover' => $file_name,
                    'nominal_dp' => convert_to_nominal($request->nominal_dp),
                    'category_id' => $request->category_id,
                    'created_by' => Auth::id(),
                ]);
                $i=1;
                foreach($request->file('image_posts') as $file)
                {
                    $ext = $file->getClientOriginalExtension();
                    $name = 'media_vendor_'.$i.'_'.$product->id.'_'.time().'.'.$ext;
                    MediaProduct::create([
                       'filename' => $name,
                        'ext' => $ext,
                        'product_id' => $product->id
                    ]);
                    $i++;
                    $file->move(public_path().'/images/uploads/vendor/', $name);
                }
                return redirect()->route('jasa.index')
                    ->with('message', 'Product Created Successfully.');
            } else {
                return redirect()->route('jasa.index')
                    ->with('message', 'Product Failed to create.');

            }

        } catch (\Exception $e){
            return $e->getMessage();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jasa = Product::where('id', $id)
            ->with('category')
            ->with('media')
            ->first();
        return Inertia::render('Jasa/Show', ['jasa' => $jasa]);
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $medias = MediaProduct::where('product_id', $product->id)->get();
            foreach ($medias as $media){
                $path_image = public_path('/images/uploads/vendor/'.$media->filename);
                if(File::exists($path_image)) {
                    File::delete($path_image);
                }
                $media->delete();

            }
            $product_image = public_path('/images/uploads/'.$product->cover);
            if(File::exists($product_image)) {
                File::delete($product_image);
            }
            $product->delete();
            $data = array(
                'success' => true,
                'message' => 'Berhasil menghapus data product',
                'data' => $product,
            );
            return redirect()->route('jasa.index')
                ->with('message', 'Product Deleted Successfully.');
        } catch (\Exception $exception) {
            $data = array(
                [
                    'success' => false,
                    'message' => 'Terjadi kesalahan : '.$exception->getMessage()
                ]
            );
            return redirect()->route('jasa.index')
                ->with('message', 'Product gagal dihapus.');
        }
    }
}
