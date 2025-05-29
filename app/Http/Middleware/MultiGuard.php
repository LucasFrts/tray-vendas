<?php  
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class MultiGuard
{
    public function handle($request, Closure $next, ...$guards)
    {
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::shouldUse($guard);
                return $next($request);
            }
        }
        return redirect()->route('login'); 
    }
}
