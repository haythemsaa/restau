<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailCampaign extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'business_id',
        'template_id',
        'name',
        'subject',
        'content',
        'variables',
        'target_type',
        'target_criteria',
        'segment_ids',
        'tier_filters',
        'status',
        'scheduled_at',
        'started_at',
        'completed_at',
        'recipients_count',
        'sent_count',
        'delivered_count',
        'opened_count',
        'clicked_count',
        'bounced_count',
        'failed_count',
        'from_name',
        'from_email',
        'reply_to',
        'track_opens',
        'track_clicks',
    ];

    protected $casts = [
        'variables' => 'array',
        'target_criteria' => 'array',
        'segment_ids' => 'array',
        'tier_filters' => 'array',
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'track_opens' => 'boolean',
        'track_clicks' => 'boolean',
    ];

    /**
     * Get the business that owns the campaign
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Get the template for the campaign
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class);
    }

    /**
     * Get all sends for this campaign
     */
    public function sends(): HasMany
    {
        return $this->hasMany(CampaignSend::class, 'campaign_id');
    }

    /**
     * Get recipients based on targeting criteria
     */
    public function getRecipients(): \Illuminate\Database\Eloquent\Collection
    {
        $query = Customer::where('business_id', $this->business_id);

        switch ($this->target_type) {
            case 'all':
                // All customers
                break;

            case 'segment':
                if ($this->segment_ids) {
                    $query->whereHas('segments', function ($q) {
                        $q->whereIn('customer_segments.id', $this->segment_ids);
                    });
                }
                break;

            case 'tier':
                if ($this->tier_filters) {
                    $query->whereIn('tier', $this->tier_filters);
                }
                break;

            case 'custom':
                if ($this->target_criteria) {
                    // Apply custom criteria
                    if (isset($this->target_criteria['min_ltv'])) {
                        $query->where('lifetime_value', '>=', $this->target_criteria['min_ltv']);
                    }
                    if (isset($this->target_criteria['min_visits'])) {
                        $query->where('visit_count', '>=', $this->target_criteria['min_visits']);
                    }
                    if (isset($this->target_criteria['at_risk'])) {
                        $query->atRisk();
                    }
                    if (isset($this->target_criteria['birthday'])) {
                        $query->birthdayThisMonth();
                    }
                }
                break;
        }

        return $query->whereNotNull('email')->get();
    }

    /**
     * Calculate and update recipients count
     */
    public function updateRecipientsCount(): int
    {
        $count = $this->getRecipients()->count();
        $this->update(['recipients_count' => $count]);
        return $count;
    }

    /**
     * Calculate delivery rate
     */
    public function getDeliveryRateAttribute(): float
    {
        if ($this->sent_count === 0) return 0;
        return round(($this->delivered_count / $this->sent_count) * 100, 2);
    }

    /**
     * Calculate open rate
     */
    public function getOpenRateAttribute(): float
    {
        if ($this->delivered_count === 0) return 0;
        return round(($this->opened_count / $this->delivered_count) * 100, 2);
    }

    /**
     * Calculate click rate
     */
    public function getClickRateAttribute(): float
    {
        if ($this->delivered_count === 0) return 0;
        return round(($this->clicked_count / $this->delivered_count) * 100, 2);
    }

    /**
     * Calculate bounce rate
     */
    public function getBounceRateAttribute(): float
    {
        if ($this->sent_count === 0) return 0;
        return round(($this->bounced_count / $this->sent_count) * 100, 2);
    }

    /**
     * Check if campaign is ready to send
     */
    public function isReadyToSend(): bool
    {
        return $this->status === 'scheduled' &&
               $this->scheduled_at &&
               $this->scheduled_at->isPast();
    }

    /**
     * Mark campaign as sending
     */
    public function markAsSending(): void
    {
        $this->update([
            'status' => 'sending',
            'started_at' => now(),
        ]);
    }

    /**
     * Mark campaign as completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'sent',
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark campaign as failed
     */
    public function markAsFailed(): void
    {
        $this->update([
            'status' => 'failed',
        ]);
    }

    /**
     * Increment sent count
     */
    public function incrementSentCount(): void
    {
        $this->increment('sent_count');
    }

    /**
     * Increment delivered count
     */
    public function incrementDeliveredCount(): void
    {
        $this->increment('delivered_count');
    }

    /**
     * Increment opened count
     */
    public function incrementOpenedCount(): void
    {
        $this->increment('opened_count');
    }

    /**
     * Increment clicked count
     */
    public function incrementClickedCount(): void
    {
        $this->increment('clicked_count');
    }

    /**
     * Increment bounced count
     */
    public function incrementBouncedCount(): void
    {
        $this->increment('bounced_count');
    }

    /**
     * Increment failed count
     */
    public function incrementFailedCount(): void
    {
        $this->increment('failed_count');
    }

    /**
     * Scope: Draft campaigns
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope: Scheduled campaigns
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope: Campaigns ready to send
     */
    public function scopeReadyToSend($query)
    {
        return $query->where('status', 'scheduled')
                     ->whereNotNull('scheduled_at')
                     ->where('scheduled_at', '<=', now());
    }

    /**
     * Scope: Sent campaigns
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }
}
