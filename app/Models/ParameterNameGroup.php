<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Traits\OrderableModel;

class ParameterNameGroup extends Model
{
    use OrderableModel;

    /**
     * Get order field name.
     * @return string
     */
    public function getOrderField()
    {
        return 'sort';
    }

    //Характеристики группы
    public function parameter_names()
    {
        return $this->hasMany(ParameterName::class, 'parameter_name_group_id', 'id');
    }

    public function parametersForCatalog($moto_id)
    {
//        $all_parameters = ParameterName::with('parameter_name_group')
        $all_parameters = ParameterName::with(array('parameter_name_group' => function ($query) {
            $query->orderBy('sort', 'asc');
        }))
//            ->orderBy('sort')
            ->get();
//        $all_parameters = ParameterNameGroup::with('parameter_names')->get();

        $product_parameters = $this->parameter_names;

        foreach ($all_parameters as $parameter) {
            $eq_parameter = $product_parameters->where('id', $parameter->id)->first();
            if ($eq_parameter) {
                $parameter->val = $eq_parameter->pivot->value;
            } else {
                $parameter->val = '';
            }

        }

        return $all_parameters;
    }
}
