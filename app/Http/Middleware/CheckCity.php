<?php

namespace App\Http\Middleware;

use App\Models\City;
use App\Models\Moto;
use Closure;

class CheckCity
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

//        if ($request->is_city) {
//            die('!');
//            return $next($request);
//        }

        $city_or_moto = $request->route('city');
//dd($city_or_moto);
        if ($city_or_moto) {

            $is_city = City::where('alias', $city_or_moto)->first();

            if ($is_city) {

                $request->request->add(['is_city' => true]);
                return $next($request);

            } else {
//                die("2");

                $request->request->add(['is_city' => false]);
                return $next($request);
            }

        } else {
//            die("!!!");
            $current_city = $request->cookie('current_city_alias');

            if ($current_city) {
//                die("!!!");
                $request->request->add(['is_city' => true]);
                return redirect($current_city);
            }

        }

        return $next($request);
    }
}
