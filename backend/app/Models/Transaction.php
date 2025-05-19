<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'carnet_id', 'product_name', 'quantity', 'total_price'
    ];

    public function carnet() {
        return $this->belongsTo(Carnet::class);
    }
}
