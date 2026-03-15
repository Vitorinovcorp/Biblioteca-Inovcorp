<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RequisicaoController;

// Rotas públicas
Route::get('/', function () {
    return view('welcome');
});

// Rotas de autenticação
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/login', [LoginController::class, 'logout'])->name('logout');

// Rotas protegidas (requerem autenticação)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Visualização de livros
    Route::get('/livros', [LivroController::class, 'index'])->name('livros.index');
    Route::get('/livros/{id}', [LivroController::class, 'show'])->name('livros.show');
    
    // Autores e Editoras
    Route::get('/autores', [AutorController::class, 'index'])->name('autores.index');
    Route::get('/editoras', [EditorController::class, 'index'])->name('editoras.index');
    
    // Requisições
    Route::get('/requisicoes', [RequisicaoController::class, 'index'])->name('requisicoes.index');
    Route::get('/requisicoes/create', [RequisicaoController::class, 'create'])->name('requisicoes.create');
    Route::post('/requisicoes', [RequisicaoController::class, 'store'])->name('requisicoes.store');
    Route::delete('/requisicoes/{id}', [RequisicaoController::class, 'destroy'])->name('requisicoes.destroy');
    
    // Rotas de Admin
    Route::middleware(['admin'])->group(function () {
        // Gestão de Livros
        Route::get('/livros/create', [LivroController::class, 'create'])->name('livros.create');
        Route::post('/livros', [LivroController::class, 'store'])->name('livros.store');
        Route::get('/livros/{id}/edit', [LivroController::class, 'edit'])->name('livros.edit');
        Route::put('/livros/{id}', [LivroController::class, 'update'])->name('livros.update');
        Route::delete('/livros/{id}', [LivroController::class, 'destroy'])->name('livros.destroy');
        
        // Gestão de Requisições
        Route::post('/requisicoes/{id}/status', [RequisicaoController::class, 'updateStatus'])
            ->name('requisicoes.status');
    });
});