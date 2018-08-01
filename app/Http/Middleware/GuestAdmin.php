<?php

namespace App\Http\Middleware;

use Closure;
Use Auth;
Use Redirect;

class GuestAdmin 
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
		
		if(!empty(Auth::user()) && (Auth::user()->user_role_id  != SUPER_ADMIN_ROLE_ID && Auth::user()->user_role_id  != SUB_ADMIN_ROLE_ID)){
			return Redirect::to('/');
		}
        return $next($request);
    }
}
