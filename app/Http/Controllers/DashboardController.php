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
        

        return Inertia::render('Dashboard', [
            'carousels' => $carousels,
        ]);
    }
}
