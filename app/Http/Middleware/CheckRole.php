<?php

namespace App\Http\Middleware;

use App\Traits\HttpResponses;
use Closure;
use Exception;
use Illuminate\Http\Request;

class CheckRole
{
    Use HttpResponses;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        
        try {
            $userRole = auth()->user()->role;

            if ($userRole == $role) {
                return $next($request);
            }

            return $this->error(null, 'You don\'t have permission to access this page', 403);
        } catch (Exception $e) {
            return $this->error(null, $e, 500);
        }
        return $next($request);
    }
}
