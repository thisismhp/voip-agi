<?php

namespace App\Http\Middleware;

use Closure;

class SetDB
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
        $serviceID = $request->header('serviceid');
        if ($this->user()->id == 1 and $this->user()->username == 'admin'){
            config(['database.connections.service.database' => "service-$serviceID"]);
        }else{
            $services = $this->user()->services;
            foreach ($services as $service) {
                if ($service->id == $serviceID) {
                    config(['database.connections.service.database' => "service-$serviceID"]);
                }
            }
        }
        return $next($request);
    }

    private function user()
    {
        return auth()->user();
    }
}
