<?php

namespace App\Http\Middleware;

use App\Models\Log;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class LogAcessoMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $resposta = $next($request);

        if (Auth::check() && !$request->is('api/*')) {
            Log::create([
                'data' => now()->toDateString(),
                'hora' => now()->toTimeString(),
                'user_id' => Auth::id(),
                'modulo' => 'Acesso',
                'objeto_id' => null,
                'alteracao' => "Acessou: " . $request->method() . " " . $request->path(),
                'ip' => $request->ip(),
                'browser' => $request->userAgent(),
            ]);
        }

        return $resposta;
    }
}