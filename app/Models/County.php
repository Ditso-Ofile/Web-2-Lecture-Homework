<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * TASK 5 - Eloquent model for the `counties` table (ORM).
 * Columns: id, cname
 */
class County extends Model
{
    protected $table = 'counties';

    // The ids come from counties.txt, they are not auto-increment
    public $incrementing = false;

    // The table has no created_at / updated_at columns
    public $timestamps = false;

    protected $fillable = ['id', 'cname'];

    // One county has many towns (towns.countyid -> counties.id)
    public function towns(): HasMany
    {
        return $this->hasMany(Town::class, 'countyid', 'id');
    }

    // All population records of the county's towns (counties -> towns -> populations)
    public function populations(): HasManyThrough
    {
        return $this->hasManyThrough(Population::class, Town::class, 'countyid', 'townid', 'id', 'id');
    }
}
