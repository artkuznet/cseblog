<?php

namespace App\Http\Middleware;

use Closure;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    private $password='i_am_admin!'; // md5 = bacb2f0c06c9d83b3e93570e568eb49c

    public function handle($request, Closure $next)
    {
        if($request->exists('password'))
        {
            if($request->input('password') === md5($this->password))
            {
                return $next($request);
            }
        }
        abort(403);
    }
}
