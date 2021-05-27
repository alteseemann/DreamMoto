<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $guarded = [];

    //^^^Отношения
    //Категории техники
    public function motos()
    {
        return $this->belongsToMany(Moto::class, 'moto_brand','brand_id','moto_id')->withPivot('catalog_description');
    }
    //Дилеры
    public function dealers()
    {
        return $this
            ->belongsToMany(Dealer::class, 'dealer_brand','brand_id','dealer_id')
            ->withPivot('moto_id');
    }
    //Элемент каталога
    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id','id');
    }
    //---Отношения

}
