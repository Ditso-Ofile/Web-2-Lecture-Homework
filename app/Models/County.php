<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'cname'];

    public function towns() { return $this->hasMany(Town::class, 'countyid'); }
}
