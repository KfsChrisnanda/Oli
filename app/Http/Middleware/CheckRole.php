<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Check if user has any of the required roles
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Redirect based on user role if access denied
        return $this->redirectBasedOnRole($user->role);
    }

    /**
     * Redirect user to appropriate page based on their role
     */
    private function redirectBasedOnRole($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->route('dashboard');
            case 'kasir':
                return redirect()->route('pos.index');
            case 'penjaga_gudang':
                return redirect()->route('products.index');
            default:
                return redirect()->route('dashboard');
        }
    }
}
