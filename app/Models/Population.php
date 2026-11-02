<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Population extends Model
{
    public $timestamps = false;
    protected $fillable = ['townid', 'ryear', 'women', 'total'];

    public function town() { return $this->belongsTo(Town::class, 'townid'); }
}
