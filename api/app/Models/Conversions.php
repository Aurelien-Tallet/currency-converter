<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversions extends Model
{
    use HasFactory;

    protected $fillable = [
        'pair_id',
    ];

    public function pair()
    {
        return $this->belongsTo(Pair::class);
    }
}
