<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'campaign_id',
        'type',
        'amount',
        'description',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * The "booted" method of the model.
     * This is where we register our model events.
     *
     * @return void
     */
    protected static function booted(): void
    {

        static::created(function (Donation $donation) {

            if ($donation->type === 'money' && $donation->amount > 0) {
                $campaign = $donation->campaign;

                if ($campaign) {
                    $campaign->increment('collected_amount', $donation->amount);

                    if ($campaign->collected_amount >= $campaign->target_amount) {
                        $campaign->status = 'finished';
                        $campaign->save();
                    }
                }
            }
        });


        static::deleted(function (Donation $donation) {
            if ($donation->type === 'money' && $donation->amount > 0) {
                $campaign = $donation->campaign;

                if ($campaign) {

                    $newCollectedAmount = $campaign->collected_amount - $donation->amount;
                    $campaign->collected_amount = max(0, $newCollectedAmount);
                    $campaign->save();
                }
            }
        });


    }

    /**
     * Get the user that owns the Donation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the campaign that the Donation belongs to.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get all of the comments for the Donation.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
