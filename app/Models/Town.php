<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'tname', 'countyid', 'countyseat', 'countylevel'];

    public function county()      { return $this->belongsTo(County::class, 'countyid'); }
    public function populations() { return $this->hasMany(Population::class, 'townid'); }
}
