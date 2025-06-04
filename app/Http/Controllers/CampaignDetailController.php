<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User; // Pastikan ini diimpor

class CampaignDetailController extends Controller
{
    /**
     * Tampilkan halaman detail donasi/kampanye.
     *
     * @param  string $slug
     * @return \Inertia\Response
     */
    public function index($slug)
    {
        // Temukan kampanye berdasarkan slug
        $campaign = Campaign::where('slug', $slug)->firstOrFail();

        // Ambil riwayat donasi untuk kampanye ini
        // Eager load user untuk mendapatkan nama donor
        $donationHistory = Donation::where('campaign_id', $campaign->id)
                                    ->with('user') // Memuat relasi user dari model Donation
                                    ->orderBy('created_at', 'desc')
                                    ->get()
                                    ->map(function ($donation) {
                                        $donorName = $donation->user ? $donation->user->name : 'Hamba Allah';
                                        $profilePhotoUrl = $donation->user ? $donation->user->profile_photo_url : null;

                                        // Logika untuk mendapatkan URL gambar atau inisial
                                        $imageUrl = $profilePhotoUrl;
                                        if (!$imageUrl) {
                                            // Jika tidak ada foto profil, buat inisial
                                            $words = explode(' ', $donorName);
                                            $initials = '';
                                            foreach ($words as $word) {
                                                $initials .= strtoupper(substr($word, 0, 1));
                                            }
                                            // Batasi inisial maksimal 2 karakter untuk placeholder
                                            $initials = substr($initials, 0, 2);

                                            // Buat URL placeholder dengan inisial
                                            // Contoh: https://placehold.co/100x100/ADD8E6/000?text=JD
                                            $imageUrl = 'https://placehold.co/100x100/ADD8E6/000?text=' . $initials;
                                        }

                                        return [
                                            'id' => $donation->id,
                                            'donor' => $donorName,
                                            'amount' => $donation->amount,
                                            'message' => $donation->description ?? 'Semoga Bermanfaat',
                                            'imageUrl' => $imageUrl, // Menggunakan URL gambar yang sudah ditentukan
                                        ];
                                    });

        // Hitung total donasi terkumpul (jumlah baris)
        $totalDonationCount = $donationHistory->count();

        // Mock data untuk Timeline Program (Anda bisa mengambil ini dari database juga)
        $mockTimelineEvents = [
        ];

        return Inertia::render('CampaignDetail', [
            'campaign' => $campaign,
            'donationHistory' => $donationHistory,
            'totalDonationCount' => $totalDonationCount,
            'timelineEvents' => $mockTimelineEvents,
        ]);
    }
}
