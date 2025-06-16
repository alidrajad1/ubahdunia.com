<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Campaign;

class NewsController extends Controller
{
     public function index()
    {
        return Inertia::render('NewsList', [
        ]);
    }

        /**
         * Ambil data kampanye dalam format JSON untuk API.
         *
         * @param  \Illuminate\Http\Request
         * @return \Illuminate\Http\JsonResponse
         */
       public function getNewsApi(Request $request)
    {
        try {
            $news = Campaign::orderBy('created_at', 'desc')->get();



            return response()->json($news);
        } catch (\Exception $e) {

            return response()->json(['message' => 'Terjadi kesalahan server.'], 500);
        }
    }
}
