<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpdateUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            
            DB::table('users')
                ->where('id', $userId)
                ->update(['estado' => 'online']);
        }
        
        return $next($request);
    }
}