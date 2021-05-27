<?php

namespace App\Http\Controllers\Dealers;

use App\Models\AdBlock;
use App\Models\Brand;
use App\Models\City;
use App\Models\Dealer;
use App\Models\Moto;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Facades\Agent;

class DealersController extends Controller
{
    public function index(Request $request)
    {

        $current_moto = Moto::where('alias', $request->route('moto_alias'))->first();

        if ($current_moto) {

            $brands = $current_moto->brands()->get();

            $dealers = Moto::with(array('dealers' => function ($query) {
                $query
                    ->with('city')
                    ->with('brands')
                    ->paginate('');
            }))
                ->where('id', $current_moto->id)
                ->first();

            $placemarks = collect();

            foreach ($dealers->dealers as $dealer) {
                $placemarkt = collect([
                    'id'          => '1',
                    'clusterName' => "1",
                    'options'     => ['preset' => 'islands#blueCircleDotIcon'],
                ]);
                $placemarks->push($placemarkt);
            }

//            ->whereHas('dealer', function ($query) use ($current_city_id) {
//                $query
//                    ->where('city_id', $current_city_id);
//            })
//                ->with('dealer.city')
//                ->orderby('price')
//                ->get();

            $page_title       = 'Купить ' . $current_moto->title_single . '. Салоны и официальные дилеры';
            $page_description = 'Список официальных дилеров и салонов ' . $current_moto->title_chego . ' - адреса, телефоны, вебсайты в нашем электронном каталоге.';
            SEOMeta::setTitle($page_title);
            SEOMeta::setDescription($page_description);

            $view = Agent::isMobile() ? 'dealers.dealers_mobile' : 'dealers.dealers';

            return view($view, [
                'brands'          => $brands,
                'current_moto'    => $current_moto,
                'current_city'    => NULL,
                'current_brand'   => NULL,
                'current_product' => NULL,
                'dealers'         => $dealers,
                'placemarks'      => $placemarks,
            ]);

        } else {

            abort('404');

        }

    }

    public function brand(Request $request)
    {

        $current_moto  = Moto::where('alias', $request->route('moto_alias'))->first();
        $current_brand = Brand::where('alias', $request->route('brand_alias'))->first();

        if ($current_brand && $current_moto) {

            $brands = $current_moto->brands()->get();

            $dealers = Moto::with(array('dealers' => function ($query) use ($current_brand) {
                $query
                    ->with('city')
                    ->whereHas('brands', function ($query) use ($current_brand) {
                        $query->where('brands.id', $current_brand->id);
                    })
                    ->with(array('brands' => function ($query) use ($current_brand) {
                            $query
                                ->where('brands.id', $current_brand->id);
                        })
                    );
            }))
                ->where('id', $current_moto->id)
                ->first();

            $placemarks = collect();

            foreach ($dealers->dealers as $dealer) {
                $placemarkt = collect([
                    'clusterName' => "1",
                    'options'     => ['preset' => 'islands#blueCircleDotIcon'],
                ]);
                $placemarks->push($placemarkt);
            }

            //для снегоходов и квадроциклов BRP добавляем модель
            if ($current_moto->alias == 'snowmobiles' && $current_brand->alias == 'brp' ) {

                $page_title       = 'Купить ' . $current_moto->title_single . ' ' . $current_brand->title_ru . ' СкиДо, Линкс. Салоны и официальные дилеры';
                $page_description = 'Список официальных дилеров и салонов ' . $current_moto->title_chego . ' ' . $current_brand->title . ' Ski-Doo и Linx, - адреса, телефоны, вебсайты в нашем электронном каталоге.';

            } elseif ($current_moto->alias == 'atv' && $current_brand->alias == 'brp' ) {

                $page_title       = 'Купить ' . $current_moto->title_single . ' ' . $current_brand->title_ru . ' Can-Am. Салоны и официальные дилеры';
                $page_description = 'Список официальных дилеров и салонов ' . $current_moto->title_chego . ' ' . $current_brand->title . ' Can-Am, - адреса, телефоны, вебсайты в нашем электронном каталоге.';

            } elseif ($current_moto->alias == 'boat-motors' && $current_brand->alias == 'brp' ) {

                $page_title       = 'Купить ' . $current_moto->title_single . ' ' . $current_brand->title_ru . ' Эвинруд. Салоны и официальные дилеры';
                $page_description = 'Список официальных дилеров и салонов ' . $current_moto->title_chego . ' ' . $current_brand->title . ' Evinrude, - адреса, телефоны, вебсайты в нашем электронном каталоге.';

            } else {

                $page_title       = 'Купить ' . $current_moto->title_single . ' ' . $current_brand->title_ru . '. Салоны и официальные дилеры';
                $page_description = 'Список официальных дилеров и салонов ' . $current_moto->title_chego . ' ' . $current_brand->title . ' - адреса, телефоны, вебсайты в нашем электронном каталоге.';

            }

            SEOMeta::setTitle($page_title);
            SEOMeta::setDescription($page_description);

            $view = Agent::isMobile() ? 'dealers.dealers_mobile' : 'dealers.dealers';

            return view($view, [
                'brands'          => $brands,
                'current_moto'    => $current_moto,
                'current_brand'   => $current_brand,
                'current_city'    => NULL,
                'current_product' => NULL,
                'dealers'         => $dealers,
                'placemarks'      => $placemarks,
            ]);

        } else {

            abort('404');

        }

    }

    public function city_index(Request $request)
    {

        $current_city = City::where('alias', $request->route('city'))->first();
        $current_moto = Moto::where('alias', $request->route('moto_alias'))->first();

        if ($current_city && $current_moto) {

            $brands = $current_moto->brands()->get();

            $current_city_id = $current_city->id;

            $dealers = Moto::with(array('dealers' => function ($query) use ($current_city_id) {
                $query
                    ->where('city_id', $current_city_id)
                    ->with('city')
                    ->with('brands');
            }))
                ->where('id', $current_moto->id)
                ->first();

            $placemarks = collect();

            foreach ($dealers->dealers as $dealer) {
                $placemark = collect([
                    'clusterName' => "1",
                    'options'     => ['preset' => 'islands#blueCircleDotIcon'],
                ]);
                $placemarks->push($placemark);
            }

//            ->whereHas('dealer', function ($query) use ($current_city_id) {
//                $query
//                    ->where('city_id', $current_city_id);
//            })
//                ->with('dealer.city')
//                ->orderby('price')
//                ->get();

            $page_title       = 'Купить ' . $current_moto->title_single . ' в ' . $current_city->where . '. Салоны и официальные дилеры';
            $page_description = 'Список официальных дилеров и салонов ' . $current_moto->title_chego . ' в ' . $current_city->where . ' - адреса, телефоны, вебсайты в нашем электронном каталоге.';
            SEOMeta::setTitle($page_title);
            SEOMeta::setDescription($page_description);

            $view = Agent::isMobile() ? 'dealers.dealers_mobile' : 'dealers.dealers';

            return view($view, [
                'brands'          => $brands,
                'current_moto'    => $current_moto,
                'current_city'    => $current_city,
                'current_brand'   => NULL,
                'current_product' => NULL,
                'dealers'         => $dealers,
                'placemarks'      => $placemarks,
            ]);

        } else {

            abort('404');

        }

    }

    public function city_brand(Request $request)
    {

        $current_city  = City::where('alias', $request->route('city'))->first();
        $current_moto  = Moto::where('alias', $request->route('moto_alias'))->first();
        $current_brand = Brand::where('alias', $request->route('brand_alias'))->first();

        if ($current_city && $current_moto && $current_brand) {

            $brands = $current_moto->brands()->get();

            $current_city_id = $current_city->id;

            $dealers = Moto::with(array('dealers' => function ($query) use ($current_brand, $current_city_id) {
                $query
                    ->where('city_id', $current_city_id)
                    ->with('city')
                    ->whereHas('brands', function ($query) use ($current_brand) {
                        $query->where('brands.id', $current_brand->id);
                    })
                    ->with(array('brands' => function ($query) use ($current_brand) {
                            $query
                                ->where('brands.id', $current_brand->id);
                        })
                    );
            }))
                ->where('id', $current_moto->id)
                ->first();

            $placemarks = collect();

            foreach ($dealers->dealers as $dealer) {
                $placemark = collect([
                    'clusterName' => "1",
                    'options'     => ['preset' => 'islands#blueCircleDotIcon'],
                ]);
                $placemarks->push($placemark);
            }

            $page_title       = 'Купить ' . $current_moto->title_single . ' ' . $current_brand->title_ru . ' в ' . $current_city->where . '. Салоны и официальные дилеры';
            $page_description = 'Список официальных дилеров и салонов ' . $current_moto->title_chego . ' ' . $current_brand->title . ' в ' . $current_city->where . ' - адреса, телефоны, вебсайты в нашем электронном каталоге.';
            SEOMeta::setTitle($page_title);
            SEOMeta::setDescription($page_description);

            $view = Agent::isMobile() ? 'dealers.dealers_mobile' : 'dealers.dealers';

            return view($view, [
                'brands'          => $brands,
                'current_moto'    => $current_moto,
                'current_city'    => $current_city,
                'current_brand'   => $current_brand,
                'current_product' => NULL,
                'dealers'         => $dealers,
                'placemarks'      => $placemarks,
            ]);

        } else {

            abort('404');

        }

    }

    public function dealer(Request $request)
    {

        $dealer_alias = $request->route('dealer_alias');

        $current_dealer = Dealer::where('alias', $dealer_alias)
            ->with('brands')
            ->with('city')
            ->with('motos')
            ->with('sales')
            ->first();

        $brands_arr = $current_dealer->brands->pluck('id')->all();
        $motos_arr  = $current_dealer->motos->pluck('id')->all();

        $new_motos = Moto::whereNotIn('id', $motos_arr)->get();

        $motos_brands = Moto::whereHas('brands', function ($query) use ($brands_arr) {
            $query->whereIn('brands.id', $brands_arr);
        })
            ->with(['brands' => function ($query) use ($brands_arr) {
                $query->whereIn('brands.id', $brands_arr);
            }])
            ->whereIn('id', $motos_arr)
            ->get();

        $current_motos = Moto::wherein('id', $motos_arr)
            ->with(['brands' => function ($query) {
                $query->orderby('title');
            }])
            ->get();

//        dd($motos_arr, $brands_arr, $motos_brands, $current_dealer);

        if ($current_dealer) {

            $dealer_title = $current_dealer->title . ' - официальный дилер ';

            $page_description = $current_dealer->title . '. ';
            if ($current_dealer->address) {
                $page_description = $page_description . 'Адрес: ' . $current_dealer->address;
            }
            if ($current_dealer->phone) {
                $page_description = $page_description . ', телефон: ' . $current_dealer->phone;
            }
            $page_description = $page_description . '. Информация о дилере.';

            foreach ($motos_brands as $key => $moto) {
                if ($key > 0) {
                    $dealer_title = $dealer_title . ', ';
                }
                $dealer_title = $dealer_title . $moto->title_chego;
            }

            SEOMeta::setTitle($dealer_title);
            SEOMeta::setDescription($page_description);

            if (Auth::id() != 1) {
                //увеличиваем количество просмотров
                $current_dealer->increment('view_count');
            }

            return view('dealers.dealer', [
                'current_motos'  => $current_motos,
                'current_dealer' => $current_dealer,
                'motos_brands'   => $motos_brands,
                'new_motos'      => $new_motos,
                'ads_sidebar'    => $this->getAds('sidebar'),
            ]);

        } else {

            abort('404');

        }

    }

    function getAds($sidebar)
    {
        $ad_block = AdBlock::where('is_active', '1')
            ->where('alias', $sidebar)
            ->first();

        return $ad_block;
    }

    public function dealer_add(Request $request)
    {

        if (Auth::check()) {

            return view('dealers.dealer_add', []);

        } else {

            abort('404');

        }

    }

    public function dealer_save(Request $request)
    {
        if ($request->edit) {
            try {

                $dealer = Dealer::find($request->dealer_id);

                $moto_id = [];

                foreach ($request->moto_id as $moto) {
                    array_push($moto_id, $moto['id']);
                }

                $brand_id = [];

                foreach ($request->brand_id as $brand) {
                    array_push($brand_id, $brand['id']);
                }

                if ($moto_id && $brand_id && $dealer) {

                    DB::transaction(function () use ($dealer, $moto_id, $brand_id) {
                        $dealer->save();

                        $dealer->motos()->attach($moto_id);
                        $dealer->brands()->attach($brand_id);
                    });

                    $response['success'] = true;

                } else {
                    $response['success'] = false;
                }

                return $response;

//            здесь редирект на страницу дилера

            } catch (\Exception $ex) {

                $response['success'] = false;

                return $response;

            }

        } else {
            try {
                $dealer = new Dealer([
                    'city_id'   => $request->city_id,
                    'title'     => $request->title,
                    'alias'     => $request->alias,
                    'address'   => $request->address,
                    'phone'     => $request->phone,
                    'site'      => $request->site,
                    'latitude'  => $request->latitude,
                    'longitude' => $request->longitude,
                ]);

                $moto_id = [];

                foreach ($request->moto_id as $moto) {
                    array_push($moto_id, $moto['id']);
                }

                $brand_id = [];

                foreach ($request->brand_id as $brand) {
                    array_push($brand_id, $brand['id']);
                }

                if ($moto_id && $brand_id) {

                    DB::transaction(function () use ($dealer, $moto_id, $brand_id) {
                        $dealer->save();

                        $dealer->motos()->sync($moto_id);
                        $dealer->brands()->sync($brand_id);
                    });

                    $redirect_path = '/dealers/' . $dealer->alias;

                    $response['success']       = true;
                    $response['redirect_path'] = $redirect_path;

                } else {
                    $response['success'] = false;
                }

                return $response;

//            здесь редирект на страницу дилера

            } catch (\Exception $ex) {

                $response['success'] = false;

                return $response;

            }

        }

    }

    public function get_brands(Request $request)
    {

        $moto_id = [];

        foreach ($request->moto_id as $moto) {
            array_push($moto_id, $moto['id']);
        }
//        dd($moto_id);

        $brands = Brand::with(['motos' => function ($query) use ($moto_id) {
            $query->wherein('motos.id', $moto_id);
        }])
            ->wherehas('motos', function ($query) use ($moto_id) {
                $query->wherein('motos.id', $moto_id);
            })
            ->orderBy('title')
            ->get();

        $response['brands'] = $brands;

        return $response;

    }

    public function dealer_check(Request $request)
    {

        $words = preg_split('/\s+/', preg_replace('/[^a-zA-Zа-яА-Я0-9 ]/ui', '', $request->title));

        if (count($words) > 0) {

            $check_list = Dealer::select('dealers.title as dealer_title', 'dealers.alias as dealer_alias', 'cities.title as city_title')
                ->leftjoin('cities', function ($join) {
                    $join->on('cities.id', '=', 'dealers.city_id');
                });

            foreach ($words as $word) {
                $like_word  = '%' . $word . '%';
                $check_list = $check_list->orWhere('dealers.title', 'like', $like_word);
            }

            $check_list = $check_list->orderBy(DB::raw('cities.title'))->get();

            $response['check_list'] = $check_list;
        }

        $response['success'] = true;

        return $response;

    }

    public function dealer_save_coords(Request $request)
    {
        try {

            $dealer            = Dealer::find($request->id);
            $dealer->latitude  = $request->latitude;
            $dealer->longitude = $request->longitude;

            $dealer->save();

            $response['success'] = true;
            $response['price']   = $product->price_catalog;

        } catch (\Exception $ex) {

            $response['success'] = false;

        }
        $response['success'] = true;

        return $response;

    }
}
