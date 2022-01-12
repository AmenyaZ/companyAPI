<?php

namespace App\Http\Middleware;

use Closure;
use Faker\Provider\ar_EG\Text;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestrictRegistrationToOneAdmin
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
        $user = DB::table('users')->select('role')->where('id',1)->first();
        
        if ($user && (int)$user->role === 1){
            // fail and redirect silently if we already have a user with that role
            $response = ['Please Contact Admin to Register You',
                            'If you have an ccount Please Login']   ;
            return response($response, 422);
        }

        return $next($request);
    }
}
