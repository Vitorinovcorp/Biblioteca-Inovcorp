<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\EditorController; 
use App\Http\Controllers\AutorController; 


Route::get('/livros', [LivroController::class, 'index']);
Route::get('/editoras', [EditorController::class, 'index']);
Route::get('/autores', [AutorController::class, 'index']);



