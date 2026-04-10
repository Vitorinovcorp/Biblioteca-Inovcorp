<?php

namespace App\Http\Controllers;

use App\Models\Encomenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EncomendaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $encomendas = Encomenda::with('user', 'itens.livro')
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } else {
            $encomendas = Encomenda::with('itens.livro')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        return view('encomendas.index', compact('encomendas'));
    }

    public function show($id)
    {
        $encomenda = Encomenda::with('user', 'itens.livro', 'itens.livro.autores')
            ->findOrFail($id);
        
        $user = Auth::user();
        
        if ($user->role !== 'admin' && $encomenda->user_id !== $user->id) {
            abort(403);
        }

        return view('encomendas.show', compact('encomenda'));
    }
}