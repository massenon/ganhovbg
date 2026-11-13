<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use DB;

class MaintananceModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            DB::connection()->getPdo();
            if(!DB::getSchemaBuilder()->hasTable('settings')){

                return $next($request);
            }else{

                if ( setting('MAINTENANCE_MODE') == 'on' && !$request->is('administration/*') && !$request->is('administration') ){

                    return response()->view('errors.503', [], 503);
                }
                return $next($request);
            }
        } catch (\Exception $e) {
            return $next($request);
            die("Could not connect to the database.  Please check your configuration. error:" . $e );
        }
    }
}
