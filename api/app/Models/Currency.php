<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    public $timestamps = false;
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


}
