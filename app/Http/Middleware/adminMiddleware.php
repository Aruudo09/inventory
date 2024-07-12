<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class adminMiddleware
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
        if(Auth::check()) {
            if (Auth::user()->userLevel == 1) {
                return $next($request);
            } else {
                $request->session()->flash('danger', 'Access Denied!');
                return redirect('/dashboard')->with('message', 'Access Denied');
            }

        } else {
            $request->session()->flash('danger', 'Access Denied!');
            return redirect('/dashboar')->with('message', 'Access Denied');
        }

        return $next($request);
    }
}
