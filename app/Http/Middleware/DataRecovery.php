<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helper\Installer\trait\ApichecktraitHelper;
use Auth;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DataRecovery
{
    use ApichecktraitHelper;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(recursion() || represent() || setting('newupdate') == 'updated3.1'){
            return redirect()->route('admin.newupdate');
        }else{
            if(setting('update_setting') == null){
                if($request->is('administration/*')){
                    return redirect()->route('admin.testinginfo');
                }else{
                    $next($request);
                }
            }
            return $next($request);
        }
    }
}
