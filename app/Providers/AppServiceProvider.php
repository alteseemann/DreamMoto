<?php

namespace App\Providers;

use App\Models\City;
use App\Models\Moto;
use App\Models\Brand;
use View;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Agent\Agent;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Тип устройства
        View::share('user_agent', new  Agent());

        //Категория техники
        View::share('motos', Moto::get()->where('is_active', '1'));

        //Текущая категория техники
        View::share('current_moto', null);

        //Все бренды
        View::share('brands', Brand::select('title','id')->get());

        //Текущий бренд
        View::share('current_brand', null);

        //Текущий продукт
        View::share('$current_product', null);

        //Список городов
        View::share('cities', collect($this->getCities()));
    }

    function getCities()
    {
        $cities = City::orderby('title')
            ->get();

        //Генерируем массив с группировкой городов по алфавиту
        $index = [];
        $current = null;
        foreach ($cities as $city) {
            $firstLetter = mb_substr($city->title, 0, 1, 'utf-8');
            if (!$current || $current['letter'] !== $firstLetter) {
                $index[] = [
                    'letter' => $firstLetter,
                    'cities' => []
                ];
                $current = &$index[count($index) - 1];
            }
            $current['cities'][] = array(
                'title' => $city->title,
                'id'    => $city->id,
                'alias' => $city->alias,
            );
        }

        return $index;
    }
}
