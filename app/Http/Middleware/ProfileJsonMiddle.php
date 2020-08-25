<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class ProfileJsonMiddle
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
        $response = $next($request) ;
        \Debugbar::enable(); // or we van disable it using word 'disable'

        if($response instanceof JsonResponse && app('debugbar')->isEnabled() && $request->has('_debug'))

        {


            $response->setData(
                $response->getData(true) + [
                    '_debugbar'=>arr::only(app('debugbar')->getData(),'queries')
                ]);
            return $response ;
        }
        else
             return $response ;

    }
}
