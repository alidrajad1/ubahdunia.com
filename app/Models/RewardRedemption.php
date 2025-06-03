<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardRedemption extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reward_id',
        'redeemed_at',
    ];

    /**
     * Get the user that owns the reward redemption.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reward that was redeemed.
     */
    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }
}
