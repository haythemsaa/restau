<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignSend extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'campaign_id',
        'customer_id',
        'recipient_email',
        'recipient_name',
        'status',
        'sent_at',
        'delivered_at',
        'opened_at',
        'clicked_at',
        'bounced_at',
        'open_count',
        'click_count',
        'provider_message_id',
        'error_message',
        'metadata',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
        'bounced_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the campaign for this send
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(EmailCampaign::class, 'campaign_id');
    }

    /**
     * Get the customer for this send
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Mark as sent
     */
    public function markAsSent(string $providerId = null): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
            'provider_message_id' => $providerId,
        ]);

        $this->campaign->incrementSentCount();
    }

    /**
     * Mark as delivered
     */
    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);

        $this->campaign->incrementDeliveredCount();
    }

    /**
     * Mark as opened
     */
    public function markAsOpened(): void
    {
        $isFirstOpen = $this->opened_at === null;

        $this->increment('open_count');
        $this->update(['opened_at' => $this->opened_at ?? now()]);

        if ($isFirstOpen) {
            $this->campaign->incrementOpenedCount();
        }
    }

    /**
     * Mark as clicked
     */
    public function markAsClicked(): void
    {
        $isFirstClick = $this->clicked_at === null;

        $this->increment('click_count');
        $this->update(['clicked_at' => $this->clicked_at ?? now()]);

        if ($isFirstClick) {
            $this->campaign->incrementClickedCount();
        }
    }

    /**
     * Mark as bounced
     */
    public function markAsBounced(string $errorMessage = null): void
    {
        $this->update([
            'status' => 'bounced',
            'bounced_at' => now(),
            'error_message' => $errorMessage,
        ]);

        $this->campaign->incrementBouncedCount();
    }

    /**
     * Mark as failed
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);

        $this->campaign->incrementFailedCount();
    }

    /**
     * Scope: Pending sends
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Sent sends
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope: Delivered sends
     */
    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    /**
     * Scope: Opened sends
     */
    public function scopeOpened($query)
    {
        return $query->whereNotNull('opened_at');
    }

    /**
     * Scope: Clicked sends
     */
    public function scopeClicked($query)
    {
        return $query->whereNotNull('clicked_at');
    }

    /**
     * Scope: Bounced sends
     */
    public function scopeBounced($query)
    {
        return $query->where('status', 'bounced');
    }

    /**
     * Scope: Failed sends
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
