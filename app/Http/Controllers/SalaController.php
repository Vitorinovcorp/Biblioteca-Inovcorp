<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaController extends Controller
{
    public function index()
    {
        $salas = Sala::whereHas('utilizadores', function($query) {
            $query->where('user_id', Auth::id());
        })->with('mensagens')->get();
        
        return view('chat.index', compact('salas'));
    }

    public function create()
    {
        $utilizadores = User::where('id', '!=', Auth::id())->get();
        return view('chat.criar-sala', compact('utilizadores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'utilizadores' => 'nullable|array',
            'utilizadores.*' => 'exists:users,id',
        ]);

        $sala = Sala::create([
            'nome' => $request->nome,
            'criado_por' => Auth::id(),
        ]);

        $sala->utilizadores()->attach(Auth::id());

        if ($request->has('utilizadores')) {
            $sala->utilizadores()->attach($request->utilizadores);
        }

        return redirect()->route('chat.conversa', $sala->id)
            ->with('success', 'Sala criada com sucesso!');
    }

    public function show($id)
    {
        $sala = Sala::with('utilizadores', 'mensagens.user')->findOrFail($id);
        
        if (!$sala->utilizadores->contains(Auth::id())) {
            abort(403, 'Você não tem acesso a esta sala.');
        }
        
        return view('chat.chat-room', compact('sala'));
    }

    public function destroy($id)
    {
        $sala = Sala::findOrFail($id);
        
        if ($sala->criado_por !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $sala->delete();
        
        return redirect()->route('chat.index')->with('success', 'Sala removida!');
    }
}