<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(){
        $total_booking = Booking::count();
        $total_vendor = Product::count();
        $total_katalog = Post::count();
        $monthly_orders = Booking::select(DB::raw('COUNT(id) as total, EXTRACT(MONTH FROM created_at) AS month'))
            ->wheredate('created_at', '>=', Carbon::now()->lastOfMonth()->subyears(1))
            ->groupBy(DB::raw('month'))
            ->get();
        $monthly_omset = Payment::select(DB::raw('SUM(nominal) as total, EXTRACT(MONTH FROM created_at) AS month'))
            ->wheredate('created_at', '>=', Carbon::now()->lastOfMonth()->subyears(1))
            ->groupBy(DB::raw('month'))
            ->get();


        $months  = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        foreach ($months as $month) {
            $data_perbulan[]= 0;
            $omset_perbulan[]=0;

        }
        foreach ($monthly_orders as $key) {
            $data_perbulan[$key->month - 1] = $key->total;//update each month with the total value
        }
        foreach ($monthly_omset as $key) {
            $omset_perbulan[$key->month - 1] = formatHargaJuta($key->total);//update each month with the total value
        }
        $sorted_jumlahorder = collect($data_perbulan)->sortBy(function ($count, $month) {
            $currentMonth = (int) \Carbon\Carbon::now()->month;
            return ($month + (13 - $currentMonth - 1)) % 12;
        });
        $sorted_jumlahomset = collect($omset_perbulan)->sortBy(function ($count, $month) {
            $currentMonth = (int) \Carbon\Carbon::now()->month;
            return ($month + (13 - $currentMonth - 1)) % 12;
        });

        $data = [
          'total_booking' => $total_booking,
          'total_vendor' => $total_vendor,
          'total_katalog' => $total_katalog,
          'order_perbulan' => $data_perbulan,
          'total_omset' => $omset_perbulan
        ];
        return Inertia::render('Dashboard', ['all_data' => $data]);
    }
}
