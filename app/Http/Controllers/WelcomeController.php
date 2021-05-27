<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\City;
use App\Models\Moto;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;

class WelcomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }


    public function index(Request $request)
    {
        //переадресация с яндекса временная
//        if (strpos(request()->server('HTTP_REFERER'), 'yandex') || strpos(request()->server('HTTP_REFERER'), 'google')) {
//            return redirect()->route('catalog.index', 'motorcycles', 302);
//        }

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
            'current_city_name' => $current_city_name,
        ]);
    }

    public function callback(Request $request)
    {
        Mail::send('emails.callback', array(
            'name'    => $request->name,
            'email'   => $request->email,
            'text' => $request->message,
        ), function ($message) {
            $message->from('contact@dreammoto.ru', 'Laravel');
            $message->to('evnik@inbox.ru')->subject('Сообщение с сайта DreamMoto');
        });

        $response['success'] = true;

        return $response;
    }

    public function load_cities()
    {
//        $cities = City::orderby('title')
//            ->get();
//
//        //Генерируем массив с группировкой городов по алфавиту
//        $index = [];
//        $current = null;
//        foreach ($cities as $city) {
//            $firstLetter = mb_substr($city->title, 0, 1, 'utf-8');
//            if (!$current || $current['letter'] !== $firstLetter) {
//                $index[] = [
//                    'letter' => $firstLetter,
//                    'cities' => []
//                ];
//                $current = &$index[count($index) - 1];
//            }
//            $current['cities'][] = array(
//                'title' => $city->title,
//                'id'    => $city->id,
//                'alias' => $city->alias,
//            );
//        }
//
//        return $index;
    }

}
