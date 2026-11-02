<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * TASK 5 - Eloquent model for the `populations` table (ORM).
 * Columns: id, townid, ryear, women, total
 */
class Population extends Model
{
    protected $table = 'populations';

    public $timestamps = false;

    protected $fillable = ['townid', 'ryear', 'women', 'total'];

    protected $casts = [
        'ryear' => 'integer',
        'women' => 'integer',
        'total' => 'integer',
    ];

    // Many population records belong to one town
    public function town(): BelongsTo
    {
        return $this->belongsTo(Town::class, 'townid', 'id');
    }

    // Calculated attribute: $population->men  (total - women)
    public function getMenAttribute(): ?int
    {
        if ($this->total === null || $this->women === null) {
            return null;
        }

        return $this->total - $this->women;
    }
}
