<?php

namespace App\Http\Controllers;

use App\Models\Autor;

class AutorController extends Controller
{
    public function index()
    {
        return view('autores');
    }
}