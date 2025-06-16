<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Campaign; // Menggunakan model News
use App\Models\Image; // Jika Anda memiliki model Image terpisah untuk gambar berita

class NewsDetailController extends Controller
{
    /**
     * Tampilkan halaman detail berita.
     *
     * @param  string $slug
     * @return \Inertia\Response
     */
    public function index($slug)
    {
        $news = Campaign::where('slug', $slug)->firstOrFail();

        // Mengambil gambar terkait berita (jika ada relasi one-to-many atau one-to-one)
        // Sesuaikan dengan struktur database Anda.
        // Contoh: jika ada kolom 'image_url' di tabel News
        $imageUrl = $news->image_url;

        // Atau jika ada relasi dengan model Image:
        // $imageUrl = $news->images->first()->url ?? null; // Asumsi ada relasi hasMany atau hasOne bernama 'images'

        return Inertia::render('NewsDetail', [
            'title' => $news->title,
            'description' => $news->description,
            'imageUrl' => $imageUrl, // Mengirim URL gambar ke halaman Inertia
        ]);
    }
}
