<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * TASK 5 - Eloquent model for the `towns` table (ORM).
 * Columns: id, tname, countyid, countyseat, countylevel
 */
class Town extends Model
{
    protected $table = 'towns';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['id', 'tname', 'countyid', 'countyseat', 'countylevel'];

    protected $casts = [
        'countyseat'  => 'boolean',
        'countylevel' => 'boolean',
    ];

    // Many towns belong to one county
    public function county(): BelongsTo
    {
        return $this->belongsTo(County::class, 'countyid', 'id');
    }

    // One town has many yearly population records
    public function populations(): HasMany
    {
        return $this->hasMany(Population::class, 'townid', 'id');
    }
}
