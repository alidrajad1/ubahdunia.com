<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;

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

        $campaign = Campaign::where('slug', $slug)->firstOrFail();


        $donationHistory = Donation::where('campaign_id', $campaign->id)
                                    ->with('user')
                                    ->orderBy('created_at', 'desc')
                                    ->get()
                                    ->map(function ($donation) {
                                        $donorName = $donation->user ? $donation->user->name : 'Hamba Allah';
                                        $profilePhotoUrl = $donation->user ? $donation->user->profile_photo_url : null;


                                        $imageUrl = $profilePhotoUrl;
                                        if (!$imageUrl) {

                                            $words = explode(' ', $donorName);
                                            $initials = '';
                                            foreach ($words as $word) {
                                                $initials .= strtoupper(substr($word, 0, 1));
                                            }

                                            $initials = substr($initials, 0, 2);


                                            $imageUrl = 'https://placehold.co/100x100/ADD8E6/000?text=' . $initials;
                                        }

                                        return [
                                            'id' => $donation->id,
                                            'donor' => $donorName,
                                            'amount' => $donation->amount,
                                            'message' => $donation->description ?? 'Semoga Bermanfaat',
                                            'imageUrl' => $imageUrl,
                                        ];
                                    });


        $totalDonationCount = $donationHistory->count();


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
