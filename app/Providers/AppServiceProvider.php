<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Carrinho;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        view()->composer('*', function ($view) {
            if (Auth::check() && !session()->has('carrinho_total_itens')) {
                $carrinho = Carrinho::where('user_id', Auth::id())
                    ->where('status', 'aberto')
                    ->first();
                $totalItens = $carrinho ? $carrinho->itens->sum('quantidade') : 0;
                session(['carrinho_total_itens' => $totalItens]);
            }
        });
    }
}