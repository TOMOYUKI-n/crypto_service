<?php

namespace App\Http\Middleware;

use Closure;

class CheckLoggedIn
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
        //もしaction実行後に何らかの処理をしたい場合はここに記載する
        //$response = $newxt($request);

        if (!Auth::check()) {
            return redirect('login');
        }

        return $next($request);
    }
}
