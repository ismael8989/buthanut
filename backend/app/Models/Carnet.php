<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carnet extends Model
{
    protected $fillable = [
        'client', 'buthanut_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}
