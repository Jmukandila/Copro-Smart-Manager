<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
  
    public function handle(Request $request, Closure $next)
{
    if (auth()->check() && !auth()->user()->is_verified) {
        // On le renvoie vers une page d'attente (waiting.blade.php) qui explique que son compte est en cours de vérification
        return redirect()->route('waiting');
    }
    return $next($request);
}
    }

