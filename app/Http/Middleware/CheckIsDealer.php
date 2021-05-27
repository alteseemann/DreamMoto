<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIsDealer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        if (Auth::user()->dealer_id == NULL){
            return redirect(route('home.settings'))->with('not_dealer','Чтобы добавлять объявления, заполните информацию о себе');
        }
        return $next($request);
    }
}
