<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        if (request()->query('cari')) {
            $allakun = BankAccount::where('nama_bank', 'like', '%' . request()->query('cari') . '%')
                ->orWhere('nomor_rekening', 'like', '%' . request()->query('cari') . '%')
                ->latest()
                ->paginate(7);
        } else {
            $allakun = BankAccount::latest()
                ->paginate(7);
        }
        return Inertia::render('BankAccount/Index', ['allakun' => $allakun]);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'nama_bank' => 'required',
            'nomor_rekening' => 'required',
            'acc_holder' => 'required',
        ])->validate();
        try {
            $bank = BankAccount::create([
                'nama_bank' => $request->nama_bank,
                'nomor_rekening' => $request->nomor_rekening,
                'acc_holder' => $request->acc_holder
            ]);
            return redirect()->back()
                ->with('message', 'Bank Account Created Successfully.');
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'nama_bank' => 'required',
            'nomor_rekening' => 'required',
        ])->validate();
        try {
            $bank = BankAccount::where('id', $id)->first();
            $bank->update(['nama_bank' => $request->nama_bank]);
            $bank->update(['nomor_rekening' => $request->nomor_rekening]);

            return redirect()->back()
                ->with('message', 'Bank Account Updated Successfully.');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
            BankAccount::find($id)->delete();
            return redirect()->back()->with('message', 'Bank Account Updated Successfully.');
    }
}
