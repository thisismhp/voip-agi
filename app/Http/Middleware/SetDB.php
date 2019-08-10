<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetDB
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $serviceID = $request->header('serviceid');
        if ($this->user()->id == 1 and $this->user()->username == 'admin'){
            config(['database.connections.service.database' => "service-$serviceID"]);
            return $next($request);
        }else{
            $services = $this->user()->services;
            foreach ($services as $service) {
                if ($service->id == $serviceID) {
                    config(['database.connections.service.database' => "service-$serviceID"]);
                    return $next($request);
                }
            }
        }
        if(substr($request->getRequestUri(),0,12) != "/api/access/"){
            return $next($request);
        }
        return abort(403);
    }

    private function user()
    {
        return auth()->user();
    }
}
