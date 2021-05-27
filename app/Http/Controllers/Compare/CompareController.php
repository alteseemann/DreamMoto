<?php

namespace App\Http\Controllers\Compare;

use App\Models\Moto;
use App\Models\ParameterNameGroup;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompareController extends Controller
{
    public function compare(Request $request)
    {
        $product_list = $request->model;
        $current_moto = Moto::where('alias', $request->route('moto_alias'))->first();

        $test = ParameterNameGroup::whereHas('parameter_names', function ($query) use ($current_moto, $product_list) {
            $query->where('parameter_names.moto_id', $current_moto->id);
        })
            ->with(array('parameter_names' => function ($query) use ($current_moto, $product_list) {
                    $query
                        ->with(array('products' => function ($query) use ($product_list) {
                            $query
                                ->whereIn('products.id', $product_list);
                        }))
                        ->where('parameter_names.moto_id', $current_moto->id);
                })
            )
            ->orderBy('sort')
            ->get();

//        $compare_list = Moto::where('alias', $request->route('moto_alias'))
//            ->whereHas('products', function ($query) use ($product_list) {
//                $query->whereIn('products.id', $product_list);
//            })
//            ->with(array('products' => function ($query) use ($product_list) {
//                    $query
//                        ->whereIn('products.id', $product_list);
//                })
//            )
//            ->first();

//        $current_moto = Moto::where('alias', $request->route('moto_alias'))
//            ->with('brands')
//            ->with('classes')
//            ->first();

        $page_title       = 'Сравнение моделей ' . $current_moto->title_chego;
        SEOMeta::setTitle($page_title);

        return view('catalog.compare', [
            'current_moto' => $current_moto,
            'test'         => $test,
        ]);

    }

    function get_compare_list(Request $request)
    {
        $product_list = $request->product_list;
        $compare_list = Moto::orderby('id')
            ->whereHas('products', function ($query) use ($product_list) {
                $query->whereIn('products.id', $product_list);
            })
            ->with(array('products' => function ($query) use ($product_list) {
                    $query
                        ->whereIn('products.id', $product_list);
                })
            )
            ->get();

//        dd($compare_list);
        $response['compare_list'] = $compare_list;

//        dd($response['compare_list']);

        return ($response);
    }
}
