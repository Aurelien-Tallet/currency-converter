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
        'rate',
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

    public function name()
    {
        return $this->name;
    }
    public function rate()
    {
        return $this->rate;
    }
    
    public static function getPairByCurrencies(Currency $currency_from, Currency $currency_to)
    {
        $from = $currency_from->id;
        $to = $currency_to->id;
        $pair = self::where('currency_id_from', $from)->orWhere('currency_id_from', $to)->where('currency_id_to', $to)->orWhere('currency_id_to', $from)->first();
        if(!$pair) return null;

        return $pair;
    }

}
