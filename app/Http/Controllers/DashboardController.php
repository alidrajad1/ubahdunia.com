<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Carousel;

class DashboardController extends Controller
{
    public function index()
    {
        $carousels = Carousel::where('is_active', 1)
                                ->orderBy('display_order')
                                ->get();
        //Ambil SEMUA data, tanpa filter is_active
        // $carousels = Carousel::all(); // Mengambil semua baris dari tabel

        // --- TAMBAHKAN BARIS INI UNTUK DEBUGGING ---
        // dd($carousels);
        // --- TAMBAHKAN BARIS INI UNTUK DEBUGGING ---
        // ------------------------------------------

        return Inertia::render('Dashboard', [
            'carousels' => $carousels,
        ]);
    }
}
