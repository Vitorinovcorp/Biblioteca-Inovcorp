<?php

namespace App\Livewire\Chat;

use App\Models\Sala;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ChatList extends Component
{
    public $salas;
    public $usuarios;
    public $search = '';

    public function mount()
    {
        $this->carregarSalas();
        $this->carregarUsuarios();
    }

    public function carregarSalas()
    {
        $this->salas = Sala::whereHas('utilizadores', function($query) {
            $query->where('user_id', Auth::id());
        })->with('mensagens')->get();
    }

    public function carregarUsuarios()
    {
        $query = User::where('id', '!=', Auth::id());

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $this->usuarios = $query->get();
    }

    public function updatedSearch()
    {
        $this->carregarUsuarios();
    }

    public function criarConversa($userId)
    {
        $user = User::find($userId);
        
        $salaExistente = Sala::whereHas('utilizadores', function($query) use ($userId) {
            $query->where('user_id', Auth::id());
        })->whereHas('utilizadores', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->first();

        if ($salaExistente) {
            return redirect()->route('chat.conversa', $salaExistente->id);
        }

        $sala = Sala::create([
            'nome' => $user->name,
            'criado_por' => Auth::id(),
        ]);

        $sala->utilizadores()->attach([Auth::id(), $userId]);

        return redirect()->route('chat.conversa', $sala->id);
    }

    public function render()
    {
        return view('livewire.chat.chat-list');
    }
}