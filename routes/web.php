<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RequisicaoController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); // CORRIGIDO: agora é /logout

// Rotas protegidas (requerem autenticação)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Resto das rotas permanece igual...
    Route::get('/livros', [LivroController::class, 'index'])->name('livros.index');
    Route::get('/livros/{id}', [LivroController::class, 'show'])->name('livros.show');
    Route::get('/autores', [AutorController::class, 'index'])->name('autores.index');
    Route::get('/editoras', [EditorController::class, 'index'])->name('editoras.index');
    Route::get('/requisicoes', [RequisicaoController::class, 'index'])->name('requisicoes.index');
    Route::get('/requisicoes/create', [RequisicaoController::class, 'create'])->name('requisicoes.create');
    Route::post('/requisicoes', [RequisicaoController::class, 'store'])->name('requisicoes.store');
    Route::delete('/requisicoes/{id}', [RequisicaoController::class, 'destroy'])->name('requisicoes.destroy');

    // Rotas de Admin
    Route::middleware(['admin'])->group(function () {
        Route::get('/livros/create', [LivroController::class, 'create'])->name('livros.create');
        Route::post('/livros', [LivroController::class, 'store'])->name('livros.store');
        Route::get('/livros/{id}/edit', [LivroController::class, 'edit'])->name('livros.edit');
        Route::put('/livros/{id}', [LivroController::class, 'update'])->name('livros.update');
        Route::delete('/livros/{id}', [LivroController::class, 'destroy'])->name('livros.destroy');
        Route::post('/requisicoes/{id}/status', [RequisicaoController::class, 'updateStatus'])->name('requisicoes.status');
    });

    // Rotas de Requisições
    Route::resource('requisicoes', RequisicaoController::class)->except(['edit', 'update']);
    Route::patch('requisicoes/{requisicao}/status', [RequisicaoController::class, 'updateStatus'])
        ->name('requisicoes.status');
    Route::post('requisicoes/verificar-disponibilidade', [RequisicaoController::class, 'verificarDisponibilidade'])
        ->name('requisicoes.verificar');
});
