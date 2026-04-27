<?php
// app/Livewire/Chat/ChatRoom.php

namespace App\Livewire\Chat;

use App\Models\Mensagem;
use App\Models\Sala;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ChatRoom extends Component
{
    public $sala;
    public $mensagens;
    public $novaMensagem = '';
    public $usuariosOnline = [];

    protected $listeners = ['mensagemEnviada' => 'carregarMensagens'];

    public function mount($salaId)
    {
        $this->sala = Sala::with('utilizadores')->findOrFail($salaId);
        $this->carregarMensagens();
    }

    public function carregarMensagens()
    {
        $this->mensagens = Mensagem::where('sala_id', $this->sala->id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function enviarMensagem()
    {
        if (empty(trim($this->novaMensagem))) {
            return;
        }

        $mensagem = Mensagem::create([
            'sala_id' => $this->sala->id,
            'user_id' => Auth::id(),
            'conteudo' => $this->novaMensagem,
            'tipo' => 'texto'
        ]);

        $this->novaMensagem = '';
        $this->carregarMensagens();
        $this->dispatch('mensagemEnviada');
        
        // Scroll para a última mensagem
        $this->dispatch('scrollToBottom');
    }

    public function getUsuariosSalaProperty()
    {
        return $this->sala->utilizadores;
    }

    public function render()
    {
        return view('livewire.chat.chat-room');
    }
}