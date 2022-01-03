<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models;
use Inertia\Inertia;
use App\Models\ProductCategory;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $allbookings = Booking::with('user')
            ->filter(\Illuminate\Support\Facades\Request::only(['search', 'kategori']))
            ->with('product')
            ->with('payment')
            ->latest()
            ->paginate(5);
        $allkategori = ProductCategory::get();
        return Inertia::render('Booking/Index', [
            'allbookings' => $allbookings,
            'filters' => \Illuminate\Support\Facades\Request::all('search', 'kategori'),
            'categories' => $allkategori
        ]);
    }
}
