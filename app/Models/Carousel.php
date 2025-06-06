<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_url',
        'caption',
        'link_url',
        'display_order',
        'is_active',
    ];
}
