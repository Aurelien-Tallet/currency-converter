<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pair extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'pair',
        'currency_id_from',
        'currency_id_to',
    ];


    public function currency_from()
    {
        return $this->belongsTo(Currency::class, 'currency_id_from');
    }

    public function currency_to()
    {
        return $this->belongsTo(Currency::class, 'currency_id_to');
    }
}
