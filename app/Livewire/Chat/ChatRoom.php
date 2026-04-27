<?php

namespace App\Livewire\Chat;

use App\Models\Mensagem;
use App\Models\Sala;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ChatRoom extends Component
{
    public $sala;
    public $mensagens = [];
    public $novaMensagem = '';

    public function mount($salaId)
    {
        $this->sala = Sala::with('utilizadores')->findOrFail($salaId);

        if (!$this->sala->utilizadores->contains('id', Auth::id())) {
            abort(403);
        }

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
    $texto = trim($this->novaMensagem);

    if ($texto === '') return;

    Mensagem::create([
        'sala_id' => $this->sala->id,
        'user_id' => Auth::id(),
        'conteudo' => $texto,
        'tipo' => 'texto'
    ]);

    $this->novaMensagem = '';

    $this->js("document.getElementById('chatInput').value = ''");

    $this->carregarMensagens();

    $this->dispatch('scrollToBottom');
}
    public function render()
    {
        return view('livewire.chat.chat-room');
    }
}