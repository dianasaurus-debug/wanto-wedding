<?php

namespace App\Http\Controllers;

use App\Models\TemaKatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class TemaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->query('cari')) {
            $alltema = TemaKatalog::where('nama_tema', 'like', '%' . request()->query('cari') . '%')
                ->latest()
                ->paginate(7);
        } else {
            $alltema = TemaKatalog::latest()
                ->paginate(7);
        }
        return Inertia::render('TemaKatalog/Index', ['alltema' => $alltema]);
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
            'nama_tema' => 'required',
        ])->validate();
        try {
            $tema = TemaKatalog::create([
                'nama_tema' => $request->nama_tema,
            ]);
            return redirect()->back()
                ->with('message', 'Kategori tema Created Successfully.');
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
            'nama_tema' => 'required',
        ])->validate();
        try {
            $tema = TemaKatalog::where('id', $id)->first();
            $tema->update(['nama_tema' => $request->nama_tema]);

            return redirect()->back()
                ->with('message', 'Kategori tema Updated Successfully.');
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
        TemaKatalog::find($id)->delete();
        return redirect()->back()->with('message', 'Kategori tema Updated Successfully.');
    }
}
