<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParameterNameTerm extends Model
{
    //^^^Отношения
    //Характеристики
    public function parameter_name()
    {
        return $this->belongsTo(ParameterName::class);
    }
}
