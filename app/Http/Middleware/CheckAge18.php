<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAge18
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check()){
            $currentUser = Auth::user();
            $dob = $currentUser->dob;
            if(is_null($dob)){
                return redirect()->route('dashboard');
            }

            $dataDob = Carbon::createFromFormat('Y-m-d H:i:s', $dob);
            $diffYear = Carbon::now()->diffInYears($dataDob);
            if($diffYear >= 18){
                return $next($request);
            }
            return redirect()->route('dashboard');
        }
        return redirect()->route('login');
    }
}
