<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessGroup extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'name',
        'description',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the businesses in this group.
     */
    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class, 'group_id');
    }
}
