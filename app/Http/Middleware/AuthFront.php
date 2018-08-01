<?php

namespace App\Http\Middleware;

use Closure;
Use Auth;
Use Redirect;

class AuthFront 
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		if (Auth::guest()){
			return Redirect::to('login');	
		}
		if(Auth::user()->user_role_id  == SUPER_ADMIN_ROLE_ID || Auth::user()->user_role_id  == SUB_ADMIN_ROLE_ID){
			return Redirect::to('/cmeshinepanel');
		}
        return $next($request);
    }
}
