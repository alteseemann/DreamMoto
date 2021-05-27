<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $guarded = [];

    //Элемент каталога
    public function dealers()
    {
        return $this->hasMany(Dealer::class, 'city_id','id');
    }
}
