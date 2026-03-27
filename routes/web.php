<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RequisicaoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleBooksController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/livros', [LivroController::class, 'index'])->name('livros.index');
    Route::get('/livros/{id}', [LivroController::class, 'show'])->name('livros.show');
    Route::get('/autores', [AutorController::class, 'index'])->name('autores.index');
    Route::get('/editoras', [EditorController::class, 'index'])->name('editoras.index');

    Route::get('/requisicoes', [RequisicaoController::class, 'index'])->name('requisicoes.index');
    Route::get('/requisicoes/create', [RequisicaoController::class, 'create'])->name('requisicoes.create');
    Route::post('/requisicoes', [RequisicaoController::class, 'store'])->name('requisicoes.store');
    Route::get('/requisicoes/{id}', [RequisicaoController::class, 'show'])->name('requisicoes.show');
    Route::delete('/requisicoes/{id}', [RequisicaoController::class, 'destroy'])->name('requisicoes.destroy');
    Route::get('/requisicoes/{requisicao}/devolver', [RequisicaoController::class, 'showDevolucaoForm'])->name('requisicoes.devolver-form');
    Route::post('/requisicoes/{requisicao}/confirmar-devolucao', [RequisicaoController::class, 'confirmarDevolucao'])->name('requisicoes.confirmar-devolucao');
    Route::get('/verificar-disponibilidade', [RequisicaoController::class, 'verificarDisponibilidade'])->name('requisicoes.verificar');

    Route::middleware(['admin'])->group(function () {

    Route::get('/livros/create', [LivroController::class, 'create'])->name('livros.create');
    Route::post('/livros', [LivroController::class, 'store'])->name('livros.store');
    Route::get('/livros/{id}/edit', [LivroController::class, 'edit'])->name('livros.edit');
    Route::put('/livros/{id}', [LivroController::class, 'update'])->name('livros.update');
    Route::delete('/livros/{id}', [LivroController::class, 'destroy'])->name('livros.destroy');

    // Adicione estas rotas para editoras
    Route::get('/editoras/create', [EditorController::class, 'create'])->name('editoras.create');
    Route::post('/editoras', [EditorController::class, 'store'])->name('editoras.store');
    Route::get('/editoras/{id}/edit', [EditorController::class, 'edit'])->name('editoras.edit');
    Route::put('/editoras/{id}', [EditorController::class, 'update'])->name('editoras.update');
    Route::delete('/editoras/{id}', [EditorController::class, 'destroy'])->name('editoras.destroy');

    // Adicione estas rotas para autores
    Route::get('/autores/create', [AutorController::class, 'create'])->name('autores.create');
    Route::post('/autores', [AutorController::class, 'store'])->name('autores.store');
    Route::get('/autores/{id}/edit', [AutorController::class, 'edit'])->name('autores.edit');
    Route::put('/autores/{id}', [AutorController::class, 'update'])->name('autores.update');
    Route::delete('/autores/{id}', [AutorController::class, 'destroy'])->name('autores.destroy');

    Route::post('/requisicoes/{id}/status', [RequisicaoController::class, 'updateStatus'])->name('requisicoes.status');
    Route::patch('/requisicoes/{requisicao}/status', [RequisicaoController::class, 'updateStatus'])->name('requisicoes.status');

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::patch('/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('toggle-admin');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
});

    Route::prefix('google-books')->name('google-books.')->group(function () {
        Route::get('/search', [GoogleBooksController::class, 'index'])->name('search');
        Route::post('/search', [GoogleBooksController::class, 'search'])->name('do-search');
        Route::post('/import', [GoogleBooksController::class, 'import'])->name('import');
    });
});

