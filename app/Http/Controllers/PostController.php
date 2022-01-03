<?php

namespace App\Http\Controllers;

use App\Models\KategoriAdat;
use App\Models\Post;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        if (request()->query('cari')) {
            $allpost = Post::where('judul', 'like', '%' . request()->query('cari') . '%')
                ->orWhere('isi', 'like', '%' . request()->query('cari') . '%')
                ->latest()
                ->with('user')
                ->with('category')
                ->with('adatcategory')
                ->paginate(7);
        } else {
            $allpost = Post::where('judul', 'like', '%' . request()->query('cari') . '%')
                ->orWhere('isi', 'like', '%' . request()->query('cari') . '%')
                ->latest()
                ->with('user')
                ->with('category')
                ->with('adatcategory')
                ->paginate(7);
        }
        $allkategori = ProductCategory::get();
        $alladat = KategoriAdat::get();
        return Inertia::render('Post/Index', ['allpost' => $allpost, 'allkategori' => $allkategori, 'alladat' => $alladat]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'cover' => 'required|mimes:jpeg,jpg,png,gif,csv,txt,pdf|max:2048',
            'judul' => 'required|string',
            'isi' => 'required',
            'category_id' => 'required'
        ]);
        try{
            if($request->hasfile('cover')) {
                $file_cover = $request->file('cover');
                $file_ext = $file_cover->getClientOriginalExtension();
                $file_name = 'post_'.time().'.'.$file_ext;
                $file_cover->move(public_path().'/images/uploads/katalog/', $file_name);
                $product = Post::create([
                    'judul' => $request->judul,
                    'isi' => $request->isi,
                    'cover' => $file_name,
                    'category_id' => $request->category_id,
                    'adatcategory_id' => $request->adatcategory_id,
                    'created_by' => Auth::id()
                ]);
                return redirect()->route('post.index')
                    ->with('message', 'Katalog Created Successfully.');
            } else {
                return redirect()->route('post.index')
                    ->with('message', 'Katalog Failed to create.');

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
        //
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
        $request->validate([
            'judul' => 'required|string',
            'isi' => 'required',
            'category_id' => 'required'
        ]);
        try{
            $post = Post::findOrFail($id);
            if($request->hasfile('cover')) {
                $path_image = public_path('images/uploads/katalog/'.$post->cover);
                if(File::exists($path_image)) {
                    File::delete($path_image);
                }
                $file_cover = $request->file('cover');
                $file_ext = $file_cover->getClientOriginalExtension();
                $file_name = 'post_'.time().'.'.$file_ext;
                $file_cover->move(public_path().'/images/uploads/katalog/', $file_name);
                $post->update([
                    'judul' => $request->judul,
                    'isi' => $request->isi,
                    'cover' => $file_name,
                    'category_id' => $request->category_id,
                    'adatcategory_id' => $request->adatcategory_id
                ]);
            } else {
                $post->update([
                    'judul' => $request->judul,
                    'isi' => $request->isi,
                    'adatcategory_id' => $request->adatcategory_id,
                ]);
            }
            return redirect()->route('post.index')
                ->with('message', 'Katalog Created Successfully.');
        } catch (\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);
            $path_image = public_path('images/uploads/'.$post->cover);
            if(File::exists($path_image)) {
                File::delete($path_image);
            }
            $post->delete();
            $data = array(
                'success' => true,
                'message' => 'Berhasil menghapus data product',
                'data' => $post,
            );
            return redirect()->route('post.index')
                ->with('message', 'Katalog deleted Successfully.');
        } catch (\Exception $exception) {
            return redirect()->route('post.index')
                ->with('message', 'Katalog gagal dihapus!.');
        }
    }
}
