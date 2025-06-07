<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Inertia\Inertia;
    use App\Models\Campaign;

    class CampaignController extends Controller
    {
        public function index()
    {


        return Inertia::render('DonationList', [
        ]);
    }

        /**
         * Ambil data kampanye dalam format JSON untuk API.
         *
         * @param  \Illuminate\Http\Request
         * @return \Illuminate\Http\JsonResponse
         */
       public function getCampaignsApi(Request $request)
    {
        try {
            $campaigns = Campaign::orderBy('created_at', 'desc')->get();



            return response()->json($campaigns);
        } catch (\Exception $e) {
             
            return response()->json(['message' => 'Terjadi kesalahan server.'], 500);
        }
    }
    }
