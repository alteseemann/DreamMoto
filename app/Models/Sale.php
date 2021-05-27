<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $guarded = [];

    //Продукт каталога
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    //Дилер
    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    //Изображения объявления
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
