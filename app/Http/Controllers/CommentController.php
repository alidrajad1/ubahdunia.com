<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Get a list of comments.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        
        $comments = Comment::with('user')->latest()->take(6)->get();


        if ($comments->isEmpty()) {
            $comments = collect([
                [
                    'id' => 1,
                    'content' => 'UbaDunia.com mempermudah untuk menyalurkan sedekah kepada mereka yang benar-benar membutuhkan. Dibantu dengan tim yang amanah, Read More...',
                    'rating' => 5,
                    'user' => [
                        'name' => 'Mas Eka Raja Iblis',
                        'profile_photo_url',
                    ],
                ],
                [
                    'id' => 2,
                    'content' => 'Sangat membantu dalam berdonasi, prosesnya cepat dan transparan. Rekomendasi sekali untuk siapa saja yang ingin berbagi kebaikan. Saya sangat puas!',
                    'rating' => 4,
                    'user' => [
                        'name' => 'Fitriani Lestari',
                        'profile_photo_url',
                    ],
                ],
                [
                    'id' => 3,
                    'content' => 'Pengalaman berdonasi yang luar biasa, mudah digunakan dan informasi yang diberikan sangat jelas. Timnya responsif dan ramah. Terima kasih UbaDunia!',
                    'rating' => 5,
                    'user' => [
                        'name' => 'Rizky Pratama',
                        'profile_photo_url',
                    ],
                ],
                [
                    'id' => 4,
                    'content' => 'Aplikasi ini sangat bagus. Semoga semakin banyak orang yang terbantu dengan adanya platform ini. Terus tingkatkan pelayanannya!',
                    'rating' => 5,
                    'user' => [
                        'name' => 'Siti Aminah',
                        'profile_photo_url',
                    ],
                ],
                [
                    'id' => 5,
                    'content' => 'Mudah dan cepat. Proses donasi tidak ribet. Sangat direkomendasikan bagi yang ingin beramal tanpa banyak kendala.',
                    'rating' => 4,
                    'user' => [
                        'name' => 'Budi Santoso',
                        'profile_photo_url',
                    ],
                ],
                [
                    'id' => 6,
                    'content' => 'Sangat menginspirasi! UbaDunia.com membantu menyalurkan bantuan ke tempat yang tepat. Semoga terus istiqomah dalam kebaikan.',
                    'rating' => 5,
                    'user' => [
                        'name' => 'Dewi Sartika',
                        'profile_photo_url',
                    ],
                ],
            ]);
        }

        return response()->json($comments);
    }
}


