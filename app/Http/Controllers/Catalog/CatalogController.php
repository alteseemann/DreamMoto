<?php

namespace App\Http\Controllers\Catalog;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\AdBlock;
use App\Models\Brand;
use App\Models\City;
use App\Models\Image;
use App\Models\Moto;
use App\Models\MotoClass;
use App\Models\ParameterName;
use App\Models\Product;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as ImageResize;

class CatalogController extends Controller
{
    function getAds($sidebar)
    {
        $ad_block = AdBlock::where('is_active', '1')
            ->where('alias', $sidebar)
            ->first();

        return $ad_block;
    }

    function get_products($current_moto, $parameters)
    {
        if (count($parameters)) {
            switch ($parameters['sort']) {

                case 'view_count':
                    $products = Product::where('moto_id', $current_moto->id)
                        ->with('brand')
                        ->with(
                            array('images' => function ($query) {
                                $query
                                    ->orderby('sort');
                            })
                        )
                        ->with('moto_class')
                        ->with(array('parameter_names' => function ($query) use ($current_moto) {
                                $query
                                    ->where('preview', 1)
                                    ->where('moto_id', $current_moto->id)
                                    ->orderby('sort');
                            })
                        )
                        ->orderby('view_count', 'desc')
                        ->paginate(15);
                    break;

                case 'price-asc':
                    $products = Product::where('moto_id', $current_moto->id)
                        ->with('brand')
                        ->with(
                            array('images' => function ($query) {
                                $query
                                    ->orderby('sort');
                            })
                        )
                        ->with('moto_class')
                        ->with(array('parameter_names' => function ($query) use ($current_moto) {
                                $query
                                    ->where('preview', 1)
                                    ->where('moto_id', $current_moto->id)
                                    ->orderby('sort');
                            })
                        )
                        ->orderby(DB::raw('ISNULL(price_catalog), price_catalog'))
                        ->paginate(15);
                    break;

                case 'price-desc':
                    $products = Product::where('moto_id', $current_moto->id)
                        ->with('brand')
                        ->with(
                            array('images' => function ($query) {
                                $query
                                    ->orderby('sort');
                            })
                        )
                        ->with('moto_class')
                        ->with(array('parameter_names' => function ($query) use ($current_moto) {
                                $query
                                    ->where('preview', 1)
                                    ->where('moto_id', $current_moto->id)
                                    ->orderby('sort');
                            })
                        )
                        ->orderby(DB::raw('ISNULL(price_catalog), price_catalog'), 'DESC')
                        ->paginate(15);
                    break;

                case 'v-asc':
                    $products = Product::where('products.moto_id', $current_moto->id)
                        ->with('brand')
                        ->with(
                            array('images' => function ($query) {
                                $query
                                    ->orderby('sort');
                            })
                        )
                        ->with('moto_class')
                        ->with(array('parameter_names' => function ($query) use ($current_moto) {
                                $query
                                    ->where('preview', 1)
                                    ->where('moto_id', $current_moto->id)
                                    ->orderby('sort');
                            })
                        )
                        ->leftjoin('parameters', function ($join) {
                            $join->on('parameters.product_id', '=', 'products.id')
                                ->join('parameter_names', 'parameter_names.id', '=', 'parameters.parameter_name_id')
                                ->where('parameter_names.alias', '=', 'v');
                        })
                        ->select(
                            'products.*'
                        )
                        ->orderBy(DB::raw('ISNULL(parameters.value), CAST(parameters.value as unsigned)'), 'ASC')
//                        ->orderBy(DB::raw('CAST(parameters.value as unsigned)'), 'ASC')
                        ->paginate(15);
                    break;

                case 'v-desc':
                    $products = Product::where('products.moto_id', $current_moto->id)
                        ->with('brand')
                        ->with(
                            array('images' => function ($query) {
                                $query
                                    ->orderby('sort');
                            })
                        )
                        ->with('moto_class')
                        ->with(array('parameter_names' => function ($query) use ($current_moto) {
                                $query
                                    ->where('preview', 1)
                                    ->where('moto_id', $current_moto->id)
                                    ->orderby('sort');
                            })
                        )
                        ->leftjoin('parameters', function ($join) {
                            $join->on('parameters.product_id', '=', 'products.id')
                                ->join('parameter_names', 'parameter_names.id', '=', 'parameters.parameter_name_id')
                                ->where('parameter_names.alias', '=', 'v');
                        })
                        ->select(
                            'products.*'
                        )
                        ->orderBy(DB::raw('ISNULL(parameters.value), CAST(parameters.value as unsigned)'), 'DESC')
//                        ->orderBy(DB::raw('CAST(parameters.value as unsigned)'), 'DESC')
                        ->paginate(15);
                    break;

                default:
                    $products = Product::where('moto_id', $current_moto->id)
                        ->with('brand')
                        ->with(
                            array('images' => function ($query) {
                                $query
                                    ->orderby('sort');
                            })
                        )
                        ->with('moto_class')
                        ->with(array('parameter_names' => function ($query) use ($current_moto) {
                                $query
                                    ->where('preview', 1)
                                    ->where('moto_id', $current_moto->id)
                                    ->orderby('sort');
                            })
                        )
                        ->orderby('view_count', 'desc')
                        ->paginate(15);
            }
        } else {
            $products = Product::where('moto_id', $current_moto->id)
                ->with('brand')
                ->with(
                    array('images' => function ($query) {
                        $query
                            ->orderby('sort');
                    })
                )
                ->with('moto_class')
                ->with(array('parameter_names' => function ($query) use ($current_moto) {
                        $query
                            ->where('preview', 1)
                            ->where('moto_id', $current_moto->id)
                            ->orderby('sort');
                    })
                )
                ->orderby('view_count', 'desc')
                ->paginate(15);
        }

        return $products;
    }

    function get_brand_products($current_moto, $parameters, $current_brand)
    {
        if (count($parameters)) {
            switch ($parameters['sort']) {

                case 'view_count':
                    $products = Product::where('moto_id', $current_moto->id)
                        ->where('brand_id', $current_brand->id)
                        ->with('brand')
                        ->with(
                            array('images' => function ($query) {
                                $query
                                    ->orderby('sort');
                            })
                        )
                        ->with('moto_class')
                        ->with(array('parameter_names' => function ($query) use ($current_moto) {
                                $query
                                    ->where('preview', 1)
                                    ->where('moto_id', $current_moto->id)
                                    ->orderby('sort');
                            })
                        )
                        ->orderby('view_count', 'desc')
                        ->paginate(15);
                    break;

                case 'price-asc':
                    $products = Product::where('moto_id', $current_moto->id)
                        ->where('brand_id', $current_brand->id)
                        ->with('brand')
                        ->with(
                            array('images' => function ($query) {
                                $query
                                    ->orderby('sort');
                            })
                        )
                        ->with('moto_class')
                        ->with(array('parameter_names' => function ($query) use ($current_moto) {
                                $query
                                    ->where('preview', 1)
                                    ->where('moto_id', $current_moto->id)
                                    ->orderby('sort');
                            })
                        )
                        ->orderby(DB::raw('ISNULL(price_catalog), price_catalog'))
                        ->paginate(15);
                    break;

                case 'price-desc':
                    $products = Product::where('moto_id', $current_moto->id)
                        ->where('brand_id', $current_brand->id)
                        ->with('brand')
                        ->with(
                            array('images' => function ($query) {
                                $query
                                    ->orderby('sort');
                            })
                        )
                        ->with('moto_class')
                        ->with(array('parameter_names' => function ($query) use ($current_moto) {
                                $query
                                    ->where('preview', 1)
                                    ->where('moto_id', $current_moto->id)
                                    ->orderby('sort');
                            })
                        )
                        ->orderby(DB::raw('ISNULL(price_catalog), price_catalog'), 'DESC')
                        ->paginate(15);
                    break;

                case 'v-asc':
                    $products = Product::where('products.moto_id', $current_moto->id)
                        ->where('brand_id', $current_brand->id)
                        ->with('brand')
                        ->with(
                            array('images' => function ($query) {
                                $query
                                    ->orderby('sort');
                            })
                        )
                        ->with('moto_class')
                        ->with(array('parameter_names' => function ($query) use ($current_moto) {
                                $query
                                    ->where('preview', 1)
                                    ->where('moto_id', $current_moto->id)
                                    ->orderby('sort');
                            })
                        )
                        ->leftjoin('parameters', function ($join) {
                            $join->on('parameters.product_id', '=', 'products.id')
                                ->join('parameter_names', 'parameter_names.id', '=', 'parameters.parameter_name_id')
                                ->where('parameter_names.alias', '=', 'v');
                        })
                        ->select(
                            'products.*'
                        )
                        ->orderBy(DB::raw('ISNULL(parameters.value), CAST(parameters.value as unsigned)'), 'ASC')
                        ->paginate(15);
                    break;

                case 'v-desc':
                    $products = Product::where('products.moto_id', $current_moto->id)
                        ->where('brand_id', $current_brand->id)
                        ->with('brand')
                        ->with(
                            array('images' => function ($query) {
                                $query
                                    ->orderby('sort');
                            })
                        )
                        ->with('moto_class')
                        ->with(array('parameter_names' => function ($query) use ($current_moto) {
                                $query
                                    ->where('preview', 1)
                                    ->where('moto_id', $current_moto->id)
                                    ->orderby('sort');
                            })
                        )
                        ->leftjoin('parameters', function ($join) {
                            $join->on('parameters.product_id', '=', 'products.id')
                                ->join('parameter_names', 'parameter_names.id', '=', 'parameters.parameter_name_id')
                                ->where('parameter_names.alias', '=', 'v');
                        })
                        ->select(
                            'products.*'
                        )
                        ->orderBy(DB::raw('ISNULL(parameters.value), CAST(parameters.value as unsigned)'), 'DESC')
                        ->paginate(15);
                    break;

                default:
                    $products = Product::where('moto_id', $current_moto->id)
                        ->where('brand_id', $current_brand->id)
                        ->with('brand')
                        ->with(
                            array('images' => function ($query) {
                                $query
                                    ->orderby('sort');
                            })
                        )
                        ->with('moto_class')
                        ->with(array('parameter_names' => function ($query) use ($current_moto) {
                                $query
                                    ->where('preview', 1)
                                    ->where('moto_id', $current_moto->id)
                                    ->orderby('sort');
                            })
                        )
                        ->orderby('view_count', 'desc')
                        ->paginate(15);
            }
        } else {
            $products = Product::where('moto_id', $current_moto->id)
                ->where('brand_id', $current_brand->id)
                ->with('brand')
                ->with(
                    array('images' => function ($query) {
                        $query
                            ->orderby('sort');
                    })
                )
                ->with('moto_class')
                ->with(array('parameter_names' => function ($query) use ($current_moto) {
                        $query
                            ->where('preview', 1)
                            ->where('moto_id', $current_moto->id)
                            ->orderby('sort');
                    })
                )
                ->orderby('view_count', 'desc')
                ->paginate(15);
        }

        return $products;
    }

    function get_products_for_class($current_moto, $parameters, $is_class)
    {
        if (count($parameters) && isset($parameters['sort'])) {
            switch ($parameters['sort']) {

                case 'view_count':
                    $products = Product::where('moto_id', $current_moto->id)
                        ->with(
                            array('images' => function ($query) {
                                $query
                                    ->orderby('sort');
                            })
                        )
                        ->whereHas('moto_class', function ($query) use ($current_moto, $is_class) {
                            $query
                                ->where('alias', $is_class->alias)
                                ->where('moto_id', $current_moto->id);
                        })
                        ->with(array('moto_class' => function ($query) use ($current_moto, $is_class) {
                                $query
                                    ->where('alias', $is_class->alias)
                                    ->where('moto_id', $current_moto->id);
                            })
                        )
                        ->with(array('parameter_names' => function ($query) use ($current_moto) {
                                $query
                                    ->where('preview', 1)
                                    ->where('moto_id', $current_moto->id)
                                    ->orderby('sort');
                            })
                        );

                    if (isset($parameters['brand']) && $parameters['brand'] != 'all') {
                        $parameter_brand = $parameters['brand'];
                        $products        = $products
                            ->whereHas('brand', function ($query) use ($parameter_brand) {
                                $query
                                    ->where('alias', $parameter_brand);
                            })
                            ->with(array('brand' => function ($query) use ($parameter_brand) {
                                    $query
                                        ->where('alias', $parameter_brand);
                                })
                            )
                            ->orderby('view_count', 'desc');
                    } else {
                        $products = $products->with('brand')
                            ->orderby('view_count', 'desc');
                    }
//                    $products = $products->paginate(15);

                    break;

                case 'price-asc':
                    $products = Product::where('moto_id', $current_moto->id)
                        ->with(
                            array('images' => function ($query) {
                                $query
                                    ->orderby('sort');
                            })
                        )
                        ->whereHas('moto_class', function ($query) use ($current_moto, $is_class) {
                            $query
                                ->where('alias', $is_class->alias)
                                ->where('moto_id', $current_moto->id);
                        })
                        ->with(array('moto_class' => function ($query) use ($current_moto, $is_class) {
                                $query
                                    ->where('alias', $is_class->alias)
                                    ->where('moto_id', $current_moto->id);
                            })
                        )
                        ->with(array('parameter_names' => function ($query) use ($current_moto) {
                                $query
                                    ->where('preview', 1)
                                    ->where('moto_id', $current_moto->id)
                                    ->orderby('sort');
                            })
                        );

                    if (isset($parameters['brand']) && $parameters['brand'] != 'all') {
                        $parameter_brand = $parameters['brand'];
                        $products        = $products
                            ->whereHas('brand', function ($query) use ($parameter_brand) {
                                $query
                                    ->where('alias', $parameter_brand);
                            })
                            ->with(array('brand' => function ($query) use ($parameter_brand) {
                                    $query
                                        ->where('alias', $parameter_brand);
                                })
                            )
                            ->orderby(DB::raw('ISNULL(price_catalog), price_catalog'));
                    } else {
                        $products = $products->with('brand')
                            ->orderby(DB::raw('ISNULL(price_catalog), price_catalog'));
                    }
//                    $products = $products->paginate(15);

                    break;

                case 'price-desc':
                    $products = Product::where('moto_id', $current_moto->id)
                        ->with(
                            array('images' => function ($query) {
                                $query
                                    ->orderby('sort');
                            })
                        )
                        ->whereHas('moto_class', function ($query) use ($current_moto, $is_class) {
                            $query
                                ->where('alias', $is_class->alias)
                                ->where('moto_id', $current_moto->id);
                        })
                        ->with(array('moto_class' => function ($query) use ($current_moto, $is_class) {
                                $query
                                    ->where('alias', $is_class->alias)
                                    ->where('moto_id', $current_moto->id);
                            })
                        )
                        ->with(array('parameter_names' => function ($query) use ($current_moto) {
                                $query
                                    ->where('preview', 1)
                                    ->where('moto_id', $current_moto->id)
                                    ->orderby('sort');
                            })
                        );

                    if (isset($parameters['brand']) && $parameters['brand'] != 'all') {
                        $parameter_brand = $parameters['brand'];
                        $products        = $products
                            ->whereHas('brand', function ($query) use ($parameter_brand) {
                                $query
                                    ->where('alias', $parameter_brand);
                            })
                            ->with(array('brand' => function ($query) use ($parameter_brand) {
                                    $query
                                        ->where('alias', $parameter_brand);
                                })
                            )
                            ->orderby(DB::raw('ISNULL(price_catalog), price_catalog'), 'DESC');
                    } else {
                        $products = $products->with('brand')
                            ->orderby(DB::raw('ISNULL(price_catalog), price_catalog'), 'DESC');
                    }
//                    $products = $products->paginate(15);

                    break;

                case 'v-asc':
                    $products = Product::where('products.moto_id', $current_moto->id)
                        ->with(
                            array('images' => function ($query) {
                                $query
                                    ->orderby('sort');
                            })
                        )
                        ->whereHas('moto_class', function ($query) use ($current_moto, $is_class) {
                            $query
                                ->where('moto_classes.alias', $is_class->alias)
                                ->where('moto_classes.moto_id', $current_moto->id);
                        })
                        ->with(array('moto_class' => function ($query) use ($current_moto, $is_class) {
                                $query
                                    ->where('moto_classes.alias', $is_class->alias)
                                    ->where('moto_classes.moto_id', $current_moto->id);
                            })
                        )
                        ->with(array('parameter_names' => function ($query) use ($current_moto) {
                                $query
                                    ->where('preview', 1)
                                    ->where('parameter_names.moto_id', $current_moto->id)
                                    ->orderby('parameter_names.sort');
                            })
                        )
                        ->leftjoin('parameters', function ($join) {
                            $join->on('parameters.product_id', '=', 'products.id')
                                ->join('parameter_names', 'parameter_names.id', '=', 'parameters.parameter_name_id')
                                ->where('parameter_names.alias', '=', 'v');
                        })
                        ->select(
                            'products.*'
                        );

                    if (isset($parameters['brand']) && $parameters['brand'] != 'all') {
                        $parameter_brand = $parameters['brand'];
                        $products        = $products
                            ->whereHas('brand', function ($query) use ($parameter_brand) {
                                $query
                                    ->where('alias', $parameter_brand);
                            })
                            ->with(array('brand' => function ($query) use ($parameter_brand) {
                                    $query
                                        ->where('alias', $parameter_brand);
                                })
                            )
                            ->orderBy(DB::raw('ISNULL(parameters.value), CAST(parameters.value as unsigned)'), 'ASC');
                    } else {
                        $products = $products->with('brand')
                            ->orderBy(DB::raw('ISNULL(parameters.value), CAST(parameters.value as unsigned)'), 'ASC');
                    }
//                    $products = $products->paginate(15);

                    break;

                case 'v-desc':
                    $products = Product::where('products.moto_id', $current_moto->id)
                        ->with(
                            array('images' => function ($query) {
                                $query
                                    ->orderby('sort');
                            })
                        )
                        ->whereHas('moto_class', function ($query) use ($current_moto, $is_class) {
                            $query
                                ->where('alias', $is_class->alias)
                                ->where('moto_id', $current_moto->id);
                        })
                        ->with(array('moto_class' => function ($query) use ($current_moto, $is_class) {
                                $query
                                    ->where('alias', $is_class->alias)
                                    ->where('moto_id', $current_moto->id);
                            })
                        )
                        ->with(array('parameter_names' => function ($query) use ($current_moto) {
                                $query
                                    ->where('preview', 1)
                                    ->where('moto_id', $current_moto->id)
                                    ->orderby('sort');
                            })
                        )
                        ->leftjoin('parameters', function ($join) {
                            $join->on('parameters.product_id', '=', 'products.id')
                                ->join('parameter_names', 'parameter_names.id', '=', 'parameters.parameter_name_id')
                                ->where('parameter_names.alias', '=', 'v');
                        })
                        ->select(
                            'products.*'
                        );

                    if (isset($parameters['brand']) && $parameters['brand'] != 'all') {
                        $parameter_brand = $parameters['brand'];
                        $products        = $products
                            ->whereHas('brand', function ($query) use ($parameter_brand) {
                                $query
                                    ->where('alias', $parameter_brand);
                            })
                            ->with(array('brand' => function ($query) use ($parameter_brand) {
                                    $query
                                        ->where('alias', $parameter_brand);
                                })
                            )
                            ->orderBy(DB::raw('ISNULL(parameters.value), CAST(parameters.value as unsigned)'), 'DESC');
                    } else {
                        $products = $products->with('brand')
                            ->orderBy(DB::raw('ISNULL(parameters.value), CAST(parameters.value as unsigned)'), 'DESC');
                    }
//                    $products = $products->paginate(15);

                    break;

                default:
                    $products = Product::where('moto_id', $current_moto->id)
                        ->with('brand')
                        ->with(
                            array('images' => function ($query) {
                                $query
                                    ->orderby('sort');
                            })
                        )
                        ->whereHas('moto_class', function ($query) use ($current_moto, $is_class) {
                            $query
                                ->where('alias', $is_class->alias)
                                ->where('moto_id', $current_moto->id);
                        })
                        ->with(array('moto_class' => function ($query) use ($current_moto, $is_class) {
                                $query
                                    ->where('alias', $is_class->alias)
                                    ->where('moto_id', $current_moto->id);
                            })
                        )
                        ->with(array('parameter_names' => function ($query) use ($current_moto) {
                                $query
                                    ->where('preview', 1)
                                    ->where('moto_id', $current_moto->id)
                                    ->orderby('sort');
                            })
                        )
                        ->orderby('view_count', 'desc');
//                        ->paginate(15);
            }
        } else {
            $products = Product::where('moto_id', $current_moto->id)
                ->with('brand')
                ->with(
                    array('images' => function ($query) {
                        $query
                            ->orderby('sort');
                    })
                )
                ->whereHas('moto_class', function ($query) use ($current_moto, $is_class) {
                    $query
                        ->where('alias', $is_class->alias)
                        ->where('moto_id', $current_moto->id);
                })
                ->with(array('moto_class' => function ($query) use ($current_moto, $is_class) {
                        $query
                            ->where('alias', $is_class->alias)
                            ->where('moto_id', $current_moto->id);
                    })
                )
                ->with(array('parameter_names' => function ($query) use ($current_moto) {
                        $query
                            ->where('preview', 1)
                            ->where('moto_id', $current_moto->id)
                            ->orderby('sort');
                    })
                )
                ->orderby('view_count', 'desc');
//                ->paginate(15);
        }

        return $products;
    }

    public function index(Request $request)
    {

        $current_moto = Moto::where('alias', $request->route('moto_alias'))->first();

        if ($current_moto) {

            $current_moto_count = Product::where('moto_id', $current_moto->id)->count();

            $page_title       = $current_moto->title . ' - ' .
                $current_moto_count . ' ' .
                trans_choice('модель|модели|моделей', $current_moto_count) .
                ', цены ' .
                Helpers::get_current_year() .
                '. Каталог новых ' .
                $current_moto->title_chego .
                ', помощь в покупке';
            $page_description = 'Купить новый ' . $current_moto->title_single . ' Вам поможет наш каталог с ценами и характеристиками.';
            $page_canonical   = $request->root() . '/' . $current_moto->alias . '/catalog';

            SEOMeta::setTitle($page_title);
            SEOMeta::setDescription($page_description);
            SEOMeta::setCanonical($page_canonical);

            $all_classes = MotoClass::where('moto_id', $current_moto->id)->get();
//            $all_classes_arr        = [];
            $all_classes_random     = [];
            $all_classes_arr['all'] = 'Все классы';

            foreach ($all_classes as $class) {
                $all_classes_arr[$class->alias] = $class->title;
                //формируем массив из случайных моделей по классам
                $class_random = Product::where('class_id', $class->id)
                    ->orderByRaw("RAND()")->first();
                if ($class_random) {
                    $all_classes_random[$class_random->id] = [];
                }
            }

            $products_for_class_random = $class_random = Product::whereIn('id', array_keys($all_classes_random))
                ->with(
                    array('images' => function ($query) {
                        $query
                            ->orderby('sort');
                    })
                )
                ->with('moto_class')
                ->get();

//            dd($products_for_class_random);

            foreach ($products_for_class_random as $product_for_class_random) {
//                dd($product_for_class_random->images->count());
                if (count($product_for_class_random->images)) {

                    $class_name = mb_strtolower($product_for_class_random->moto_class->seo_title . ' ' . $current_moto->title);
                    $class_name = mb_strtoupper(mb_substr($class_name, 0, 1)) . mb_substr($class_name, 1, mb_strlen($class_name));

                    $all_classes_random[$product_for_class_random->id] = [
                        'image'       => $product_for_class_random->images[0]->path,
                        'class_alias' => $product_for_class_random->moto_class->alias,
                        'class_name'  => $class_name,
                    ];
                } else {
                    unset($all_classes_random[$product_for_class_random->id]);
                }
            }

//            dd($all_classes_random);

            $brands = $current_moto->brands()->get();

            $parameters = $request->only(['sort']);

            $products = $this->get_products($current_moto, $parameters);


//            dd(collect($all_classes_random));
//            dd(collect($all_classes_random)->join('moto_classes', 'product.class_id', '=', 'moto_classes.id'));

            return view('catalog.catalog', [
                'brands'             => $brands,
                'current_moto'       => $current_moto,
                'current_brand'      => NULL,
                'current_product'    => NULL,
                'products'           => $products,
                'all_classes'        => $all_classes,
                'all_classes_arr'    => $all_classes_arr,
                'all_classes_random' => collect($all_classes_random),
                'parameters'         => $parameters,
                'ads_sidebar'        => $this->getAds('sidebar'),
            ]);

        } else {

            abort('404');

        }

    }

    public function brands(Request $request)
    {

        //$brand_or_class = $request->route('brand_alias');
        $requestArray = explode('/',$request->url());
        $brand_or_class = array_pop($requestArray);

        //Проверим, принадлежит ли параметр классу
        $is_class = MotoClass::where('alias', $brand_or_class)->first();

        if ($is_class) {

            //если "класс", перенаправляем на страницу классов

            $current_moto    = Moto::where('alias', $request->route('moto_alias'))->first();
            $current_moto_id = $current_moto->id;

            $current_class = MotoClass::where('alias', $brand_or_class)
                ->whereHas('moto', function ($query) use ($current_moto_id) {
                    $query->where('motos.id', $current_moto_id);
                })
                ->first();

            if ($current_moto) {

                $all_classes        = MotoClass::where('moto_id', $current_moto->id)->get();
                $all_classes_random = [];
                $all_classes_arr    = [];
                foreach ($all_classes as $class) {
                    $all_classes_arr[$class->alias] = $class->title;
                    $class_random                   = Product::where('class_id', $class->id)
                        ->orderByRaw("RAND()")->first();
                    if ($class_random) {
                        $all_classes_random[$class_random->id] = [];
                    }
                }

                $products_for_class_random = $class_random = Product::whereIn('id', array_keys($all_classes_random))
                    ->with(
                        array('images' => function ($query) {
                            $query
                                ->orderby('sort');
                        })
                    )
                    ->with('moto_class')
                    ->get();

                foreach ($products_for_class_random as $product_for_class_random) {
                    if (count($product_for_class_random->images)) {

                        $class_name = mb_strtolower($product_for_class_random->moto_class->seo_title . ' ' . $current_moto->title);
                        $class_name = mb_strtoupper(mb_substr($class_name, 0, 1)) . mb_substr($class_name, 1, mb_strlen($class_name));

                        $all_classes_random[$product_for_class_random->id] = [
                            'image'       => $product_for_class_random->images[0]->path,
                            'class_alias' => $product_for_class_random->moto_class->alias,
                            'class_name'  => $class_name,
                        ];
                    } else {
                        unset($all_classes_random[$product_for_class_random->id]);
                    }
                }

                $brands                = $current_moto->brands()->get();
                $all_brands_arr        = [];
                $all_brands_arr['all'] = 'Все марки';
                foreach ($brands as $brand) {
                    $all_brands_arr[$brand->alias] = $brand->title;
                }

                $parameters = $request->only(['sort', 'brand']);

                if (isset($parameters['brand']) && isset($parameters['sort'])) {
                    if ($parameters['brand'] == 'all') {
                        $current_brand = null;
                    } else {
                        $current_brand = Brand::where('alias', $parameters['brand'])->first();
                    }
                } else {
                    $current_brand = null;
                }

                $products = $this->get_products_for_class($current_moto, $parameters, $is_class)->paginate(15);

                $current_moto_count = $this->get_products_for_class($current_moto, $parameters, $is_class)->count();

                //Заглавная буква заголовка
                $title = mb_strtolower($is_class->seo_title . ' ' . $current_moto->title);
                $title = mb_strtoupper(mb_substr($title, 0, 1)) . mb_substr($title, 1, mb_strlen($title));

                $page_title       = $title . ($current_brand ? ' ' . $current_brand->title : '') . ' - ' .
                    $current_moto_count . ' ' .
                    trans_choice('модель|модели|моделей', $current_moto_count) .
                    ', цены ' . Helpers::get_current_year() . '. Где купить, дилеры';
                $page_description = $title . ($current_brand ? ' ' . $current_brand->title : '') . ', которые можно купить у официальных дилеров на сегодняшний день.';

                if (count($parameters) === 0 || !$current_brand) {
                    $canonical = route('catalog.brands', [$current_moto->alias, $is_class->alias]);
                } else {
                    $canonical = route('catalog.brands', [$current_moto->alias, $is_class->alias]) . '?sort=view_count&brand=' . $current_brand->alias;
                }

                SEOMeta::setTitle($page_title);
                SEOMeta::setDescription($page_description);
                SEOMeta::setCanonical($canonical);

                return view('catalog.class', [
                    'brands'             => $brands,
                    'all_brands_arr'     => $all_brands_arr,
                    'current_moto'       => $current_moto,
                    'current_class'      => $current_class,
                    'current_brand'      => $current_brand,
                    'current_product'    => NULL,
                    'products'           => $products,
                    'title'              => $title,
                    'all_classes'        => $all_classes,
                    'all_classes_arr'    => $all_classes_arr,
                    'all_classes_random' => collect($all_classes_random),
                    'parameters'         => $parameters,
                    'ads_sidebar'        => $this->getAds('sidebar'),
                ]);

            } else {

                abort('404');

            }

        } else {

            $current_moto = Moto::where('alias', $request->route('moto_alias'))->first();

            $current_moto_id = $current_moto->id;

            $current_brand = Brand::where('alias', $request->route('brand_alias'))
                ->whereHas('motos', function ($query) use ($current_moto_id) {
                    $query->where('motos.id', $current_moto_id);
                })
                ->with(array('motos' => function ($query) use ($current_moto_id) {
                        $query
                            ->where('motos.id', $current_moto_id);
                    })
                )
                ->first();

            if ($current_brand) {

                $all_classes            = MotoClass::where('moto_id', $current_moto->id)->get();
                $all_classes_random     = [];
                $all_classes_arr        = [];
                $all_classes_arr['all'] = 'Все классы';
                foreach ($all_classes as $class) {
                    $all_classes_arr[$class->alias] = $class->title;
                    $class_random                   = Product::where('class_id', $class->id)
                        ->orderByRaw("RAND()")->first();
                    if ($class_random) {
                        $all_classes_random[$class_random->id] = [];
                    }
                }

                $products_for_class_random = $class_random = Product::whereIn('id', array_keys($all_classes_random))
                    ->with(
                        array('images' => function ($query) {
                            $query
                                ->orderby('sort');
                        })
                    )
                    ->with('moto_class')
                    ->get();

                foreach ($products_for_class_random as $product_for_class_random) {
                    if (count($product_for_class_random->images)) {

                        $class_name = mb_strtolower($product_for_class_random->moto_class->seo_title . ' ' . $current_moto->title);
                        $class_name = mb_strtoupper(mb_substr($class_name, 0, 1)) . mb_substr($class_name, 1, mb_strlen($class_name));

                        $all_classes_random[$product_for_class_random->id] = [
                            'image'       => $product_for_class_random->images[0]->path,
                            'class_alias' => $product_for_class_random->moto_class->alias,
                            'class_name'  => $class_name,
                        ];
                    } else {
                        unset($all_classes_random[$product_for_class_random->id]);
                    }
                }

                $current_city_alias = Cookie::get('current_city_alias');
                $current_city       = City::where('alias', $current_city_alias)->first();

                $current_moto_count = Product::where('moto_id', $current_moto->id)
                    ->where('brand_id', $current_brand->id)
                    ->count();

                //для снегоходов и квадроциклов BRP добавляем модель
                if ($current_moto->alias == 'snowmobiles' && $current_brand->alias == 'brp') {

                    $page_title       = $current_moto->title . ' ' . $current_brand->title_ru . ' СкиДо, Линкс' . ' - ' .
                        $current_moto_count . ' ' .
                        trans_choice('модель|модели|моделей', $current_moto_count) .
                        ', цены ' . Helpers::get_current_year() . ' на новые ' . $current_moto->title . ' ' . $current_brand->title . ' Ski-Doo и Lynx';
                    $page_description = 'Новые ' . mb_strtolower($current_moto->title) . ' ' . $current_brand->title . ' Ski-Doo и Linx, которые можно купить у официальных дилеров на сегодняшний день.';

                } elseif ($current_moto->alias == 'atv' && $current_brand->alias == 'brp') {

                    $page_title       = $current_moto->title . ' ' . $current_brand->title_ru . ' КанАм' . ' - ' .
                        $current_moto_count . ' ' .
                        trans_choice('модель|модели|моделей', $current_moto_count) .
                        ', цены ' . Helpers::get_current_year() . ' на новые ' . $current_moto->title . ' ' . $current_brand->title . ' Can-Am';
                    $page_description = 'Новые ' . mb_strtolower($current_moto->title) . ' ' . $current_brand->title . ' Can-Am, которые можно купить у официальных дилеров на сегодняшний день.';

                } elseif ($current_moto->alias == 'boat-motors' && $current_brand->alias == 'brp') {

                    $page_title       = $current_moto->title . ' Эвинруд' . ' - ' .
                        $current_moto_count . ' ' .
                        trans_choice('модель|модели|моделей', $current_moto_count) .
                        ', цены ' . Helpers::get_current_year() . ' на новые ' . $current_moto->title . ' Evinrude';
                    $page_description = 'Новые ' . mb_strtolower($current_moto->title) . ' Evinrude, которые можно купить у официальных дилеров на сегодняшний день.';

                } else {

                    $page_title       = $current_moto->title . ' ' . $current_brand->title_ru . ' - ' .
                        $current_moto_count . ' ' .
                        trans_choice('модель|модели|моделей', $current_moto_count) .
                        ', цены ' . Helpers::get_current_year() . ' на новые ' . $current_moto->title . ' ' . $current_brand->title;
                    $page_description = 'Новые ' . mb_strtolower($current_moto->title) . ' ' . $current_brand->title . ', которые можно купить у официальных дилеров на сегодняшний день.';

                }

                $canonical = route('catalog.brands', [$current_moto->alias, $current_brand->alias]);

                SEOMeta::setTitle($page_title);
                SEOMeta::setDescription($page_description);
                SEOMeta::setCanonical($canonical);

                $brands = $current_moto->brands()->get();

                $all_products = Product::where('moto_id', $current_moto->id)
                    ->where('brand_id', $current_brand->id)
                    ->orderby('title')
                    ->get();

                $parameters = $request->only(['sort']);

                if(Auth::check()) {
                    $products = Product::where('moto_id', $current_moto->id)
                        ->where('brand_id', $current_brand->id)
                        ->with('brand')
                        ->with(
                            array('images' => function ($query) {
                                $query
                                    ->orderby('sort');
                            })
                        )
                        ->with('moto_class')
                        ->with(array('parameter_names' => function ($query) use ($current_moto) {
                                $query
                                    ->where('preview', 1)
                                    ->where('moto_id', $current_moto->id)
                                    ->orderby('sort');
                            })
                        )
                        ->orderby('alias')
                        ->get();
                } else {
                    $products = $this->get_brand_products($current_moto, $parameters, $current_brand);
                }

                return view('catalog.catalog', [
                    'brands'             => $brands,
                    'current_moto'       => $current_moto,
                    'current_brand'      => $current_brand,
                    'current_product'    => NULL,
                    'current_city'       => $current_city,
                    'products'           => $products,
                    'all_products'       => $all_products,
                    'all_classes'        => $all_classes,
                    'all_classes_arr'    => $all_classes_arr,
                    'all_classes_random' => collect($all_classes_random),
                    'parameters'         => $parameters,
                    'ads_sidebar'        => $this->getAds('sidebar'),
                ]);

            } else {

                abort('404');

            }

        }

    }

    public function product(Request $request)
    {

        $current_moto = Moto::where('alias', $request->route('moto_alias'))->first();

        $current_brand = Brand::where('alias', $request->route('brand_alias'))->first();

        $current_product = Product::where('alias', $request->route('product_alias'))
            ->with('moto_class')
            ->where('moto_id', $current_moto->id)
            ->where('brand_id', $current_brand->id)
            ->first();

        if ($current_product) {
            $same_products = null;
            //объем
            if (isset($current_product->parameter_names()->where('parameter_names.alias', 'v')->first()->pivot)) {
                $v = $current_product->parameter_names()->where('parameter_names.alias', 'v')->first()->pivot->value;

                $different = 200;

                if ($v < 600) {
                    $different = 150;
                }
                if ($v <= 400) {
                    $different = 90;
                }
                if ($v <= 149) {
                    $different = 40;
                }

                //похожие модели
                $same_products = Product::select('products.id', 'products.moto_id', 'products.brand_id', 'products.title', 'products.alias')
                    ->where('products.moto_id', $current_moto->id)
                    ->where('products.id', '!=', $current_product->id)
                    ->where('products.class_id', $current_product->class_id)
                    ->join('parameters', function ($join) use ($v, $different) {
                        $join->on('parameters.product_id', '=', 'products.id')
                            ->join('parameter_names', 'parameter_names.id', '=', 'parameters.parameter_name_id')
                            ->where('parameter_names.alias', '=', 'v')
                            ->where('parameters.value', '>=', (integer)$v - $different)
                            ->where('parameters.value', '<=', (integer)$v + $different);
                    })
                    ->with('moto')
                    ->with('brand')
                    ->with(array('images' => function ($query) {
                            $query
                                ->orderby('images.sort');
                        })
                    )
                    ->orderBy('products.view_count')
                    ->get();
            }

//            dd($same_products);

            $page_title       = $current_brand->title . ' ' . $current_product->title . ' - цена ' . Helpers::get_current_year() . ', технические характеристики, фото';
            $page_description = mb_strtoupper(mb_substr($current_moto->title_single, 0, 1)) . mb_substr($current_moto->title_single, 1, mb_strlen($current_moto->title_single)) . ' ' . $current_product->title . '. Информация о модели, характеристики, цена, официальные дилеры';

            SEOMeta::setTitle($page_title);
            SEOMeta::setDescription($page_description);

            $brands = $current_moto->brands()->get();

            $all_products = Product::where('moto_id', $current_moto->id)
                ->where('brand_id', $current_brand->id)
                ->orderby('title')
                ->get();

            $all_classes            = MotoClass::where('moto_id', $current_moto->id)->get();
            $all_classes_arr        = [];
            $all_classes_arr['all'] = 'Выберите класс ' . $current_moto->title_chego;
            foreach ($all_classes as $class) {
                $all_classes_arr[$class->alias] = $class->title;
            }

            $product_images = $current_product->images()->orderby('sort')->get();

            $parameters = $current_product->parametersForCatalog($current_moto->id);

            if (Auth::id() != 1) {
                //увеличиваем количество просмотров
                $current_product->increment('view_count');
            }

            return view('catalog.product', [
                'brands'          => $brands,
                'current_moto'    => $current_moto,
                'current_brand'   => $current_brand,
                'current_product' => $current_product,
                'products'        => null,
                'same_products'   => $same_products,
                'all_products'    => $all_products,
                'all_classes_arr' => $all_classes_arr,
                'product_images'  => $product_images,
                'parameters'      => $parameters,
                'ads_sidebar'     => $this->getAds('sidebar'),
            ]);

        } else {

            abort('404');

        }

    }

    public function product_edit(Request $request)
    {

        $current_moto = Moto::where('alias', $request->route('moto_alias'))->first();

        $current_brand = Brand::where('alias', $request->route('brand_alias'))->first();

        $current_product = Product::where('alias', $request->route('product_alias'))
            ->where('moto_id', $current_moto->id)
            ->where('brand_id', $current_brand->id)
            ->first();

        if ($current_product && Auth::check()) {

            $brands = $current_moto->brands()->get();

            $all_products = Product::where('moto_id', $current_moto->id)
                ->where('brand_id', $current_brand->id)
                ->orderby('title')
                ->get();

            $product_images = $current_product->images()->orderby('sort')->get();

            $parameters = $current_product->parametersForCatalog($current_moto->id);

            $current_product->increment('view_count');

            return view('catalog.product_edit', [
                'brands'          => $brands,
                'current_moto'    => $current_moto,
                'current_brand'   => $current_brand,
                'current_product' => $current_product,
                'products'        => null,
                'all_products'    => $all_products,
                'product_images'  => $product_images,
                'parameters'      => $parameters,
            ]);

        } else {

            abort('404');

        }

    }

    public function parameters_change(Request $request)
    {
        $current_product  = Product::find($request->current_product);
        $groups           = $request->parameters;
        $parameter_values = array();

        foreach ($groups as $parameters) {

            foreach ($parameters['parameter_names'] as $parameter) {
                if ($parameter['val']) {
                    $parameter_values[$parameter['id']] = array('value' => $parameter['val']);
                }
            }

        }

        $current_product->parameter_names()->sync($parameter_values);

        return $parameter_values;
    }

    public function images_save(Request $request)
    {
//dd($request->deleted);
        $current_product = Product::find($request->current_product);

//        $images = $request->file('images');
        $images = $request->images;

//        dd($images);
        if (isset($images)) {
            for ($i = 0; $i < count($images); $i++) {

                if ($images[$i]['id'] == '0') {

                    //кладем в хранилище
                    $image_path = Storage::disk('public')
                        ->putFile('images/catalog/' . $current_product->id, $images[$i]['image']);
                    //ресайзим
                    $new_image = ImageResize::make(Storage::disk('public')->get($image_path))->fit(900, 600);
                    //если картинка png, делаем белую подложку
                    if ($new_image->mime() == 'image/png') {
                        $canvas    = ImageResize::canvas(900, 600, 'ffffff');
                        $new_image = $canvas->insert($new_image);
                    }
                    $new_image->encode('jpg', 95);


                    //пересохраняем
                    Storage::disk('public')->put($image_path, $new_image);

                    $path = new Image([
                        'path' => $image_path,
                        'sort' => $i,
                    ]);
                    $current_product->images()->save($path);

                } else {
                    $current_product->images()->find($images[$i]['id'])->update([
                        'sort' => $i,
                    ]);
                }

            }
        }

        $deleted = $request->deleted;

        if (isset($deleted)) {
            for ($i = 0; $i < count($deleted); $i++) {

                $dir_name         = pathinfo($deleted[$i]['path'])['dirname'];
                $find_file        = pathinfo($deleted[$i]['path'])['filename'];
                $files            = Storage::disk('public')->files($dir_name);
                $files_for_delete = [];

                //формируем список файлов для удаления
                foreach ($files as $file) {
                    $pos = strpos($file, $find_file);
                    if ($pos !== false) {
                        array_push($files_for_delete, $file);
                    }
                }

                //удаление файлов изображений
                Storage::disk('public')->delete($files_for_delete);
                //удаление записи об изображении
                $image = Image::find($deleted[$i]['id']);
                $image->delete();

            }
        }

        $response['success'] = true;

        return $response;
    }

    public function product_add(Request $request)
    {
        $current_moto = Moto::where('alias', $request->route('moto_alias'))
            ->with('brands')
            ->with('classes')
            ->first();

        if ($current_moto && Auth::check()) {

            return view('catalog.product_add', [
                'current_moto' => $current_moto,
            ]);

        } else {

            abort('404');

        }
    }

    public function product_save(Request $request)
    {
        try {
            $product = new Product([
                'moto_id'       => $request->moto_id,
                'brand_id'      => $request->brand_id,
                'class_id'      => $request->class_id,
                'title'         => $request->title,
                'alias'         => $request->alias,
                'price_catalog' => $request->price_catalog,
                'description'   => $request->description,
            ]);

            $product->save();

            $moto  = Moto::where('id', $request->moto_id)->first();
            $brand = Brand::where('id', $request->brand_id)->first();

            $redirect_path = '/' . $moto->alias . '/catalog/' . $brand->alias . '/' . $request->alias . '/edit';

            $response['success']       = true;
            $response['redirect_path'] = $redirect_path;

        } catch (\Exception $ex) {

            $response['success'] = false;

        }

        return $response;

    }

    public function price_save(Request $request)
    {

        try {

            $product                = Product::find($request->id);
            $product->price_catalog = $request->price_catalog;
            $product->price_date = date('Y-m-d H:i:s');

            $product->save();

            $response['success'] = true;
            $response['price']   = $product->price_catalog;

        } catch (\Exception $ex) {

            $response['success'] = false;

        }
        $response['success'] = true;

        return $response;

    }

    public function class_save(Request $request)
    {
        try {

            $product           = Product::find($request->id);
            $product->class_id = $request->class_id;

            $product->save();

            $response['success'] = true;
            $response['class']   = $product->moto_class->title;

        } catch (\Exception $ex) {

            $response['success'] = false;

        }
        $response['success'] = true;

        return $response;

    }

    public function view_count($url)
    {
        $options = array(
            'http' => array(
                'method' => 'GET',
                'header' => array(
                    'Content-Type: application/x-yametrika+json',
                    "Authorization: OAuth AgAAAAAWJRnHAAX5LvgowyKIFki1gC3Xj0cytAk",
                ),
            ),
        );

        $context = stream_context_create($options);

        $url = 'https://api-metrika.yandex.ru/stat/v1/data';

        $parameters = [
            "ids"               => "51529445",                            // номер счётчика метрики
            'metrics'           => 'ym:pv:pageviews,ym:pv:users', // данные по: страницам и количеству просмотров
            'dimensions'        => 'ym:pv:URLHash',               // группировка по URLHash
            "date1"             => date("Y-m-d"),                  // с какой даты получить отчёт
            'accuracy'          => 'full',                        // точная статистика (без округления)
            'limit'             => '100000',                      // максимальный лимит данных
            'proposed_accuracy' => 'false'                        // без округления данных
        ];

        array_walk($parameters, create_function('&$key, $value', '$key="$value=$key";'));
        $parameters = implode($parameters, '&');

        $url = $url . '?&' . $parameters;

        $metrikaRequest = file_get_contents($url, false, $context);

        if (!empty($metrikaRequest)) {
            dd(collect(json_decode($metrikaRequest)->data[0]));//->pluck('dimensions'));//("https://dreammoto.ru/motorcycles/catalog"));
        };
    }

}
