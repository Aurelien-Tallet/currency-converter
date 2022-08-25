<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    public $timestamps = false;
    private $id;
    protected $fillable = [
        'name',
        'symbol',
        'code',
    ];

    public function name()
    {
        return $this->name;
    }

    public function symbol()
    {
        return $this->symbol;
    }

    public function code()
    {
        return $this->code;
    }
    public function pair()
    {
        return $this->hasMany(Pair::class);
    }

    public static function getByCode($code)
    {
        return self::where('code', $code)->first();
    }
}
