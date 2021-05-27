<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotoClass extends Model
{
    //^^^Отношения
    //Термины характеристик
    public function products()
    {
        return $this->hasMany(Product::class, 'class_id', 'id');
    }
    //Типы техники
    public function moto()
    {
        return $this->belongsTo(Moto::class);
    }
}
