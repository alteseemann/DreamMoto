<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    protected $guarded = [];

    //***Отношения
    //Бренды
    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'dealer_brand','dealer_id','brand_id')
            ->withPivot('moto_id');
    }
    //фильтрация по pivot стобцу moto_id
    public function filter_brands($moto_id){
        return $this->brands()->wherePivot('moto_id',$moto_id)->select('moto_id','brand_id')->get();
    }
    //Город
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    //Категории техники
    public function motos()
    {
        return $this->belongsToMany(Moto::class, 'moto_dealer','dealer_id','moto_id');
    }
    //Объявления
    public function sales()
    {
        return $this->hasMany(Sale::class, 'dealer_id','id');
    }
    //Отношения***
}
