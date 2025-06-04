<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Inertia\Inertia;
    use App\Models\Campaign;

    class CampaignController extends Controller
    {
        public function index()
    {
        // Di sini Anda bisa mengambil data donasi dari database
        // Contoh:
        // $availableDonations = Donation::where('status', 'available')->get();

        return Inertia::render('DonationList', [ // Pastikan nama komponen Vue sesuai
            // 'availableDonations' => $availableDonations, // Kirim data jika ada
        ]);
    }

        /**
         * Ambil data kampanye dalam format JSON untuk API.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\JsonResponse
         */
       public function getCampaignsApi(Request $request)
    {
        try {
            $campaigns = Campaign::orderBy('created_at', 'desc')->get();

            // --- HAPUS BARIS INI UNTUK DEBUGGING ---
            // dd($campaigns);
            // ------------------------------------------

            return response()->json($campaigns);
        } catch (\Exception $e) {
            // Tangani error dan log untuk debugging lebih lanjut
            return response()->json(['message' => 'Terjadi kesalahan server.'], 500);
        }
    }
    }
