<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
// use App\Models\Donation; // Jika Anda memiliki model Donasi, import di sini

class VolunteerController extends Controller
{
    /**
     * Tampilkan halaman donasi yang tersedia.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        // Di sini Anda bisa mengambil data donasi dari database
        // Contoh:
        // $availableDonations = Donation::where('status', 'available')->get();

        return Inertia::render('Volunteer', [ // Pastikan nama komponen Vue sesuai
            // 'availableDonations' => $availableDonations, // Kirim data jika ada
        ]);
    }
}
