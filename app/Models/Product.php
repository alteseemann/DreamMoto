<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    //^^^Отношения
    //Категория продукта
    public function moto()
    {
        return $this->belongsTo(Moto::class);
    }

    //Бренд
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    //Объявления о продаже
    public function sales()
    {
        return $this->hasMany(Sale::class, 'product_id', 'id');
    }
    //Технические характеристики
//    public function parameters()
//    {
//        return $this->hasMany(Parameter::class, 'product_id','id');
//    }
    //Характеристики
    public function parameter_names()
    {
        return $this->belongsToMany(ParameterName::class, 'parameters', 'product_id', 'parameter_name_id')->withPivot('value');
    }

    //Изображения продукта
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    //Классы техники
    public function moto_class()
    {
        return $this->belongsTo(MotoClass::class, 'class_id', 'id');
    }

    //---Отношения

    //Характеристики
    public function parametersForCatalog($moto_id)
    {
//        $all_parameters = ParameterName::with('parameter_name_group')

        $all_parameters = ParameterNameGroup::
        whereHas('parameter_names', function ($query) use ($moto_id) {
            $query->where('moto_id', $moto_id);
        })
            ->with(array('parameter_names' => function ($query) use ($moto_id) {
                $query
                    ->with('parameter_name_terms')
                    ->where('moto_id', $moto_id)
                    ->orderBy('sort');
            }))
            ->orderBy('sort')
            ->get();

//        $all_parameters = ParameterNameGroup::with('parameter_names')->get();

        $product_parameters = $this->parameter_names;

        foreach ($all_parameters as $parameter_group) {
            foreach ($parameter_group->parameter_names as $parameter) {

                $eq_parameter = $product_parameters->where('id', $parameter->id)->first();
                if ($eq_parameter) {
                    $parameter->val = $eq_parameter->pivot->value;
                } else {
                    $parameter->val = '';
                }

            }
        }

        return $all_parameters;
    }

    public function scopeForSale($query)
    {
        return $query
            ->select(
                'sales.id as sale_id',
                'sales.price as sale_price',
                'products.title as product_title',
                'products.alias as product_alias',
                'brands.title as brand_title',
                'brands.alias as brand_alias'
            )
            ->join('sales', 'products.id', '=', 'sales.product_id')
            ->join('motos', 'motos.id', '=', 'products.moto_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id');
    }


}
