<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use App\Models\admin;

class AuthAdmin
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

      if(empty($request->session()->get('login'))){
        if(Route::currentRouteName() != "admin.login"){
          return redirect()->route('admin.login');
        }
      }else{
        $username = $request->session()->all()["username"][0];
        $check_admin = admin::where('username', 'like', $username)->get()->count();
        if($check_admin){
          if(Route::currentRouteName() == "admin.login"){
            return redirect()->route('admin.index');
          }
        }else{
          $request->session()->forget('username');
          $request->session()->forget('login');
          return redirect()->route('member.index');
        }
      }
      return $next($request);
    }
}
