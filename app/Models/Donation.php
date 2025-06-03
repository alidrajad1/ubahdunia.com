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
        // 'amount' => 'decimal:2', // Anda bisa mengaktifkan ini jika ingin casting otomatis
    ];

    /**
     * The "booted" method of the model.
     * This is where we register our model events.
     *
     * @return void
     */
    protected static function booted(): void
    {
        // When a Donation is created, update the associated Campaign's collected_amount.
        static::created(function (Donation $donation) {
            // Only update if the donation type is 'money' and has an amount.
            // You might want to adjust this logic if 'goods' or 'labor' also contribute to collected_amount
            // in a quantifiable way (e.g., estimated value).
            if ($donation->type === 'money' && $donation->amount > 0) {
                $campaign = $donation->campaign; // Get the associated campaign

                if ($campaign) {
                    $campaign->increment('collected_amount', $donation->amount);
                    // You might also want to check if collected_amount exceeds target_amount
                    // and update campaign status to 'finished' if it does.
                    // For example:
                    if ($campaign->collected_amount >= $campaign->target_amount) {
                        $campaign->status = 'finished';
                        $campaign->save();
                    }
                }
            }
        });

        // When a Donation is deleted, decrement the associated Campaign's collected_amount.
        static::deleted(function (Donation $donation) {
            if ($donation->type === 'money' && $donation->amount > 0) {
                $campaign = $donation->campaign;

                if ($campaign) {
                    // Ensure collected_amount does not go below zero
                    $newCollectedAmount = $campaign->collected_amount - $donation->amount;
                    $campaign->collected_amount = max(0, $newCollectedAmount);
                    $campaign->save();
                }
            }
        });

        // Optional: When a Donation is updated (e.g., amount changes), adjust collected_amount.
        // This is more complex as you need to consider the old amount vs new amount.
        // For simplicity, we'll focus on created and deleted for now.
        // If you need this, let me know, and I can provide the logic.
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
