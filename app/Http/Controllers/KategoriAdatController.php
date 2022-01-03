<?php

namespace App\Http\Controllers;

use App\Models\KategoriAdat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class KategoriAdatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->query('cari')) {
            $alladat = KategoriAdat::where('nama_kategori', 'like', '%' . request()->query('cari') . '%')
                ->latest()
                ->paginate(7);
        } else {
            $alladat = KategoriAdat::latest()
                ->paginate(7);
        }
        return Inertia::render('KategoriAdat/Index', ['alladat' => $alladat]);
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
        Validator::make($request->all(), [
            'nama_kategori' => 'required',
        ])->validate();
        try {
            $kategoriadat = KategoriAdat::create([
                'nama_kategori' => $request->nama_kategori,
            ]);
            return redirect()->back()
                ->with('message', 'Kategori adat Created Successfully.');
        } catch (\Exception $e) {
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
        Validator::make($request->all(), [
            'nama_kategori' => 'required',
        ])->validate();
        try {
            $kategoriadat = KategoriAdat::where('id', $id)->first();
            $kategoriadat->update(['nama_kategori' => $request->nama_kategori]);

            return redirect()->back()
                ->with('message', 'Kategori adat Updated Successfully.');
        } catch (\Exception $e) {
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
        KategoriAdat::find($id)->delete();
        return redirect()->back()->with('message', 'Kategori adat Updated Successfully.');
    }
}
