<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (in_array(auth()->user()->role, ['admin', 'teacher'])) {
            return $next($request);
        }

        return redirect('login')->with('error', 'У вас нет доступа администратора');
    }
}
