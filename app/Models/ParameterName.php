<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Traits\OrderableModel;

class ParameterName extends Model
{
    use OrderableModel;

    protected $guarded = [];

    //^^^Отношения
    //Тип техники
    public function moto()
    {
        return $this->belongsTo(Moto::class);
    }
    //Термины характеристик
    public function parameter_name_terms()
    {
        return $this->hasMany(ParameterNameTerm::class, 'parameter_name_id','id');
    }
    //Группы характеристик
    public function parameter_name_group()
    {
        return $this->belongsTo(ParameterNameGroup::class);
    }
    //Продукты
    public function products()
    {
        return $this->belongsToMany(Product::class, 'parameters', 'parameter_name_id', 'product_id')->withPivot('value');
    }
    //---Отношения

    /**
     * Get order field name.
     * @return string
     */
    public function getOrderField()
    {
        return 'sort';
    }

    public function scopeForCatalog($query)
    {
        return $query
            ->leftJoin('parameters', 'parameter_names.id', '=', 'parameters.parameter_name_id');
    }
}
