<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTemplate extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'business_id',
        'name',
        'slug',
        'description',
        'category',
        'subject',
        'content',
        'plain_text',
        'available_variables',
        'default_values',
        'is_predefined',
        'is_active',
        'preview_image',
    ];

    protected $casts = [
        'available_variables' => 'array',
        'default_values' => 'array',
        'is_predefined' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the business that owns the template
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Get campaigns using this template
     */
    public function campaigns(): HasMany
    {
        return $this->hasMany(EmailCampaign::class, 'template_id');
    }

    /**
     * Replace variables in content
     */
    public function renderContent(array $variables = []): string
    {
        $content = $this->content;
        $mergedVariables = array_merge($this->default_values ?? [], $variables);

        foreach ($mergedVariables as $key => $value) {
            $content = str_replace("{{" . $key . "}}", $value, $content);
        }

        return $content;
    }

    /**
     * Replace variables in subject
     */
    public function renderSubject(array $variables = []): string
    {
        $subject = $this->subject;
        $mergedVariables = array_merge($this->default_values ?? [], $variables);

        foreach ($mergedVariables as $key => $value) {
            $subject = str_replace("{{" . $key . "}}", $value, $subject);
        }

        return $subject;
    }

    /**
     * Scope: Active templates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Predefined templates
     */
    public function scopePredefined($query)
    {
        return $query->where('is_predefined', true);
    }

    /**
     * Scope: Custom templates
     */
    public function scopeCustom($query)
    {
        return $query->where('is_predefined', false);
    }

    /**
     * Scope: By category
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
