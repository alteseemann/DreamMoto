<?php

namespace App\Http\Controllers\Sales;

use App\Helpers\Helpers;
use App\Http\Sections\AdBlocks;
use App\Models\adBlock;
use App\Models\Brand;
use App\Models\City;
use App\Models\Moto;
use App\Models\Product;
use App\Models\Sale;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;

class SaleController extends Controller
{
    function getAdBlock($alias)
    {
        $ad_block = AdBlock::where('is_active', '1')
            ->where('alias', $alias)
            ->first();

        return $ad_block;
    }

    public function parameter1(Request $request)
    {

        if ($request->is_city) {

            $current_city_name = Cookie::get('current_city_name');

            $current_city = City::where('alias', $request->route('city'))->first();

            $motos = Moto::get()->where('is_active', '1');

            $page_title = '';

            foreach ($motos as $index => $moto) {
                if ($index > 0) {
                    $page_title = $page_title . ', ' . $moto->title;
                } else {
                    $page_title = $page_title . $moto->title;
                }
            }

            if ($current_city_name) {
                $page_title = $page_title . ' в ' . $current_city->where;
            }

            $page_title = $page_title . '. Цены ' . Helpers::get_current_year();

            $page_description = 'Каталог и помощь в покупке ';

            foreach ($motos as $index => $moto) {
                if ($index > 0) {
                    $page_description = $page_description . ', ' . $moto->title_chego;
                } else {
                    $page_description = $page_description . $moto->title_chego;
                }
            }

            if ($current_city_name) {
                $page_description = $page_description . ' в ' . $current_city->where;
            }
            $page_description = $page_description . '. Актуальные цены на ' . Helpers::get_current_year() . ' год. Официальные дилеры.';

            SEOMeta::setTitle($page_title);
            SEOMeta::setDescription($page_description);

            return view('welcome', [
                'current_moto'      => NULL,
                'current_product'   => NULL,
                'current_city_name' => $current_city_name,
            ]);

        } else {

            $current_moto = Moto::where('alias', $request->route('city'))->first();

            if ($current_moto) {

                $brands = $current_moto->brands()->get();

                $sales = Sale::whereHas('product', function ($query) use ($current_moto) {
                    $query
                        ->whereHas('moto', function ($query) use ($current_moto) {
                            $query->where('motos.id', $current_moto->id);
                        });
                })
                    ->with(['product' => function ($query) use ($current_moto) {
                        $query
                            ->with('moto')
                            ->with('brand')
                            ->with('images');
                    }])
                    ->with('dealer.city')
                    ->with('images')->paginate(15);

                $current_city_name = Cookie::get('current_city_name');

                SEOMeta::setTitle($current_city_name);

            } else {

                abort('404');

            }

            return view('sales.sales', [
                'brands'            => $brands,
                'current_moto'      => $current_moto,
                'current_product'   => NULL,
                'current_brand'     => NULL,
                'products'          => NULL,
                'sales'             => $sales,
                'current_city_name' => $current_city_name,
                'ads_sidebar'       => $this->getAdBlock('sidebar_top'),
            ]);

        }

    }

    public function parameter2(Request $request)
    {

        if ($request->is_city) {

            $current_city    = City::where('alias', $request->route('city'))->first();
            $current_moto = Moto::where('alias', $request->route('moto_alias'))->first();

            if ($current_moto && $current_city) {

                $brands = $current_moto->brands()->get();

                $current_city_name = Cookie::get('current_city_name');

                $current_city_id = $current_city->id;

                $sales = Sale::whereHas('product', function ($query) use ($current_moto) {
                    $query
                        ->whereHas('moto', function ($query) use ($current_moto) {
                            $query->where('motos.id', $current_moto->id);
                        });
                })
                    ->whereHas('dealer', function ($query) use ($current_city_id) {
                        $query
                            ->where('city_id', $current_city_id);
                    })
                    ->with(['product' => function ($query) use ($current_moto) {
                        $query
                            ->with('moto')
                            ->with('brand')
                            ->with('images');
                    }])
                    ->with('dealer.city')
                    ->with('images')->paginate(15);

                SEOMeta::setTitle($current_city_name);

            } else {

                abort('404');

            }

            return view('sales.sales', [
                'brands'            => $brands,
                'current_moto'      => $current_moto,
                'current_product'   => NULL,
                'current_brand'     => NULL,
                'products'          => NULL,
                'sales'             => $sales,
                'current_city_name' => $current_city_name,
            ]);

        } else {

            $current_moto = Moto::where('alias', $request->route('city'))->first();

            $current_brand = Brand::where('alias', $request->route('moto_alias'))->first();

            if ($current_moto && $current_brand) {

                $brands = $current_moto->brands()->get();

                $products = Product::where('moto_id', $current_moto->id)
                    ->where('brand_id', $current_brand->id)
                    ->with('brand')
                    ->orderby('title')
                    ->get();

                $all_products = Product::where('moto_id', $current_moto->id)
                    ->where('brand_id', $current_brand->id)
                    ->orderby('title')
                    ->get();

                $sales = Sale::whereHas('product', function ($query) use ($current_moto, $current_brand) {
                    $query
                        ->whereHas('moto', function ($query) use ($current_moto) {
                            $query->where('motos.id', $current_moto->id);
                        })
                        ->whereHas('brand', function ($query) use ($current_brand) {
                            $query->where('brands.id', $current_brand->id);
                        });
                })
                    ->with(['product' => function ($query) use ($current_moto) {
                        $query
                            ->with('moto')
                            ->with('brand')
                            ->with('images');
                    }])
                    ->with('dealer.city')
                    ->with('images')->paginate(15);

//                dd($sales);

                return view('sales.sales', [
                    'brands'          => $brands,
                    'current_moto'    => $current_moto,
                    'current_brand'   => $current_brand,
                    'current_product' => NULL,
                    'sales'           => $sales,
                    'products'        => $products,
                    'all_products'    => $all_products,
                ]);

            } else {

                abort('404');

            }

        }

    }

    public function parameter3(Request $request)
    {

        if ($request->is_city) {

            $current_moto = Moto::where('alias', $request->route('moto_alias'))->first();
            $current_city    = City::where('alias', $request->route('city'))->first();
            $current_brand = Brand::where('alias', $request->route('brand_alias'))->first();

            if ($current_city && $current_moto && $current_brand) {

                $brands = $current_moto->brands()->get();

                $products = Product::where('moto_id', $current_moto->id)
                    ->where('brand_id', $current_brand->id)
                    ->with('brand')
                    ->orderby('title')
                    ->get();

                $all_products = Product::where('moto_id', $current_moto->id)
                    ->where('brand_id', $current_brand->id)
                    ->orderby('title')
                    ->get();

                $current_city_id = $current_city->id;

                $sales = Sale::whereHas('product', function ($query) use ($current_moto, $current_brand) {
                    $query
                        ->whereHas('moto', function ($query) use ($current_moto) {
                            $query->where('motos.id', $current_moto->id);
                        })
                        ->whereHas('brand', function ($query) use ($current_brand) {
                            $query->where('brands.id', $current_brand->id);
                        });
                })
                    ->whereHas('dealer', function ($query) use ($current_city_id) {
                        $query
                            ->where('city_id', $current_city_id);
                    })
                    ->with(['product' => function ($query) use ($current_moto) {
                        $query
                            ->with('moto')
                            ->with('brand')
                            ->with('images');
                    }])
                    ->with('dealer.city')
                    ->with('images')->paginate(15);

                return view('sales.sales', [
                    'brands'          => $brands,
                    'current_moto'    => $current_moto,
                    'current_brand'   => $current_brand,
                    'current_product' => NULL,
                    'sales'           => $sales,
                    'products'        => $products,
                    'all_products'    => $all_products,
                ]);

            } else {

                abort('404');

            }

        } else {

//            dd('!');

            $current_moto    = Moto::where('alias', $request->route('city'))->first();
            $current_brand   = Brand::where('alias', $request->route('moto_alias'))->first();
            $current_product = Product::where('alias', $request->route('brand_alias'))->first();

            if ($current_moto && $current_brand && $current_product) {

                $brands = $current_moto->brands()->get();

                $products = Product::where('moto_id', $current_moto->id)
                    ->where('brand_id', $current_brand->id)
                    ->with('brand')
                    ->orderby('title')
                    ->get();

                $all_products = Product::where('moto_id', $current_moto->id)
                    ->where('brand_id', $current_brand->id)
                    ->orderby('title')
                    ->get();

                $sales = Sale::whereHas('product', function ($query) use ($current_moto, $current_product) {
                    $query
                        ->whereHas('moto', function ($query) use ($current_moto) {
                            $query->where('motos.id', $current_moto->id);
                        })
                        ->where('id', $current_product->id);
                })
                    ->with(['product' => function ($query) use ($current_moto) {
                        $query
                            ->with('moto')
                            ->with('brand')
                            ->with('images');
                    }])
                    ->with('dealer.city')
                    ->with('images')->paginate(15);

                return view('sales.sales', [
                    'brands'          => $brands,
                    'current_moto'    => $current_moto,
                    'current_brand'   => $current_brand,
                    'current_product' => $current_product,
                    'sales'           => $sales,
                    'products'        => $products,
                    'all_products'    => $all_products,
                ]);

            } else {

                abort('404');

            }

        }

    }

    public function parameter4(Request $request)
    {

        if ($request->is_city) {
//die("!!");
            $current_city    = City::where('alias', $request->route('city'))->first();
            $current_moto    = Moto::where('alias', $request->route('moto_alias'))->first();
            $current_brand   = Brand::where('alias', $request->route('brand_alias'))->first();
            $current_product = Product::where('alias', $request->route('product_alias'))->first();

            if ($current_city && $current_moto && $current_brand && $current_product) {

                $brands = $current_moto->brands()->get();

                $products = Product::where('moto_id', $current_moto->id)
                    ->where('brand_id', $current_brand->id)
                    ->with('brand')
                    ->orderby('title')
                    ->get();

                $all_products = Product::where('moto_id', $current_moto->id)
                    ->where('brand_id', $current_brand->id)
                    ->orderby('title')
                    ->get();

                $current_city_id = $current_city->id;

//                $dealers =

                $sales = Sale::whereHas('product', function ($query) use ($current_moto, $current_product) {
                    $query
                        ->whereHas('moto', function ($query) use ($current_moto) {
                            $query->where('motos.id', $current_moto->id);
                        })
                        ->where('id', $current_product->id);
                })
                    ->with(['product' => function ($query) use ($current_moto) {
                        $query
                            ->with('moto')
                            ->with('brand')
                            ->with('images');
                    }])
                    ->whereHas('dealer', function ($query) use ($current_city_id) {
                        $query
                            ->where('city_id', $current_city_id);
                    })
                    ->with('dealer.city')
                    ->with('images')->paginate(15);

//                $sales = Sale::where('product_id', $current_product->id)
//                    ->whereHas('dealer', function ($query) use ($current_city_id) {
//                        $query
//                            ->where('city_id', $current_city_id);
//                    })
//                    ->with('dealer.city')
//                    ->orderby('price')
//                    ->get();

                return view('sales.sales', [
                    'brands'          => $brands,
                    'current_moto'    => $current_moto,
                    'current_brand'   => $current_brand,
                    'current_product' => $current_product,
                    'sales'           => $sales,
                    'products'        => $products,
                    'all_products'    => $all_products,
                ]);

            } else {

                abort('404');

            }

        } else {

            abort('404');

        }

    }

    public function index(Request $request)
    {

        $current_moto = Moto::where('alias', $request->route('moto_alias'))->first();

        $current_brand = Brand::where('alias', $request->route('brand_alias'))->first();

        if ($current_moto) {

            $brands = $current_moto->brands()->get();

//            $sales = Sale::with(array('product' => function($query) {
//                $query->with('moto');
//                $query->with('brand');
//            }))
//                //->where('moto_id', '1')
//                ->get();

//            $sales = $current_moto->sales(array())
//                ->orderby('price', 'asc')
//                ->get();

            $all_products = Product::where('moto_id', $current_moto->id)
                ->where('brand_id', $current_brand->id)
                ->orderby('title')
                ->get();

            $sales = Product::ForSale()
                ->where('motos.id', $current_moto->id)
                ->get();

            return view('sales.sales', [
                'brands'          => $brands,
                'current_moto'    => $current_moto,
                'current_brand'   => NULL,
                'current_product' => NULL,
//                'products'        => $products,
                'sales'           => $sales,
                'all_products'    => $all_products,
            ]);

        } else {

            abort('404');

        }

    }
}
