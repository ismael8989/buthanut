<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        'carnet_id', 'token',
    ];

    public function carnet() {
        return $this->belongsTo(Carnet::class);
    }
}
