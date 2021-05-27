<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Moto extends Model
{
    //***Отношения
    //Бренды
    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'moto_brand','moto_id','brand_id');
    }
    //Дилеры
    public function dealers()
    {
        return $this->belongsToMany(Dealer::class, 'moto_dealer','moto_id','dealer_id');
    }
    //Модели каталога
    public function products()
    {
        return $this->hasMany(Product::class, 'moto_id','id');
    }
    //Названия технических характеристик
    public function parameter_names()
    {
        return $this->hasMany(ParameterName::class, 'moto_id','id');
    }
    //Объявления через модели
    public function sales()
    {
        return $this->hasManyThrough(Sale::class, Product::class, 'moto_id', 'product_id', 'id');
    }
    //Классы
    public function classes()
    {
        return $this->hasMany(MotoClass::class, 'moto_id','id');
    }
    //Отношения***

    public function scopeForSale($query)
    {
        return $query
            ->with('sales')
            ->join('products', 'motos.id', '=', 'products.moto_id');
    }
}
