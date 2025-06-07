<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class DonationController extends Controller
{
    /**
     * Menampilkan formulir donasi untuk kampanye tertentu.
     *
     * @param string $slug 
     * @return \Inertia\Response
     */
    public function showDonationForm(string $slug)
    {
        $campaign = Campaign::where('slug', $slug)->firstOrFail();

        $paymentMethods = [
            ['id' => 'qris', 'name' => 'QRIS', 'logo' => '/storage/icon/payment_qris.png'],
            ['id' => 'bca', 'name' => 'BCA', 'logo' => '/storage/icon/payment_bca.png'],
            ['id' => 'bri', 'name' => 'BRI', 'logo' => '/storage/icon/payment_bri.png'],
            ['id' => 'mandiri', 'name' => 'Mandiri', 'logo' => '/storage/icon/payment_mandiri.png'],
            ['id' => 'shopeepay', 'name' => 'ShopeePay', 'logo' => '/storage/icon/payment_shopeepay.png'],
        ];

        $user = Auth::user();
        $userData = null;
        if ($user) {
            $userData = [
                'id' => $user->id ?? null,
                'name' => $user->name ?? 'Guest',
                'email' => $user->email ?? null,
            ];
        }

        return Inertia::render('Donation/DonationPage', [
            'campaign' => $campaign,
            'paymentMethods' => $paymentMethods,
            'user' => $userData
        ]);
    }

    /**
     * Memproses pengiriman donasi.
     *
     * @param Request $request
     * @param string $slug Slug kampanye
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processDonation(Request $request, string $slug)
    {
        $campaign = Campaign::where('slug', $slug)->firstOrFail();

        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'payment_method' => 'required|string',
            'hide_name' => 'boolean',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            $donation = Donation::create([
                'user_id' => Auth::id(),
                'campaign_id' => $campaign->id,
                'type' => 'money',
                'amount' => $request->input('amount'),
                'description' => $request->input('description', 'Donasi untuk ' . $campaign->title),
                'status' => 'completed',
            ]);

            return redirect()->route('donation.success', ['donation' => $donation->id]) // Ganti 'id' menjadi 'donation'
                             ->with('success', 'Donasi Anda berhasil dicatat sebagai processed. Silakan lanjutkan ke langkah selanjutnya.');

        } catch (\Exception $e) {
            Log::error("Error processing donation for campaign {$slug}: " . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memproses donasi. Silakan coba lagi.']);
        }
    }

    /**
     * Halaman sukses donasi.
     * @param Donation $donation
     * @return \Inertia\Response
     */
    public function donationSuccess(Donation $donation)
    {
        return Inertia::render('Donation/Success', [
            'donation' => $donation,
        ]);
    }
}
