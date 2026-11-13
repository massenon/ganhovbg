<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use DB;

class HttpsProtocolMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            DB::connection()->getPdo();
            if(!DB::getSchemaBuilder()->hasTable('settings')){

                return $next($request);
            }else{

                if(setting('FORCE_SSL') == 'on'){
                if (!$request->secure()) {
                    return redirect()->secure($request->getPathInfo());
                }
                return $next($request);
            }else{
                return $next($request);
            }
            }
        } catch (\Exception $e) {
            return $next($request);
            die("Could not connect to the database.  Please check your configuration. error:" . $e );
        }
        
       
    }
}
