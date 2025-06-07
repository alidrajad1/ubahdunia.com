<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;


class AvailableDonationController extends Controller
{
    /**
     * Tampilkan halaman donasi yang tersedia.
     *
     * @return \Inertia\Response
     */
    public function index()
    {


        return Inertia::render('AvailableDonation', [  
        ]);
    }
}
