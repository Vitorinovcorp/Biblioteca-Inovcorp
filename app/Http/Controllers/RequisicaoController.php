<?php

namespace App\Http\Controllers;

use App\Models\Requisicao;
use App\Models\Livro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequisicaoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
    
        if ($user && $user->role === 'admin') {
            $requisicoes = Requisicao::with('user', 'livro')
                ->latest()
                ->paginate(15);
        } else {
            $requisicoes = Requisicao::where('user_id', Auth::id())
                ->with('livro')
                ->latest()
                ->paginate(15);
        }
        
        return view('requisicoes.index', compact('requisicoes'));
    }

    public function create()
    {
        $livrosDisponiveis = Livro::with('autores', 'editora')
            ->whereDoesntHave('requisicoes', function($query) {
                $query->where('status', 'aprovada')
                    ->where('data_inicio', '<=', now())
                    ->where('data_fim', '>=', now());
            })
            ->get();

        return view('requisicoes.create', compact('livrosDisponiveis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'livro_id' => 'required|exists:livros,id',
            'data_inicio' => 'required|date|after_or_equal:today',
            'data_fim' => 'required|date|after:data_inicio',
            'observacoes' => 'nullable|string|max:500',
        ]);

        $livro = Livro::findOrFail($request->livro_id);
        
        if (!$this->livroDisponivelPara($request->livro_id, $request->data_inicio, $request->data_fim)) {
            return back()
                ->with('error', 'Este livro não está disponível para o período selecionado.')
                ->withInput();
        }

        $requisicoesPendentes = Requisicao::where('user_id', Auth::id())
            ->where('status', 'pendente')
            ->count();

        if ($requisicoesPendentes >= 3) {
            return back()
                ->with('error', 'Você já tem 3 requisições pendentes. Aguarde a aprovação antes de fazer novas.')
                ->withInput();
        }

        Requisicao::create([
            'user_id' => Auth::id(),
            'livro_id' => $request->livro_id,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim,
            'observacoes' => $request->observacoes,
            'status' => 'pendente',
        ]);

        return redirect()->route('requisicoes.index')
            ->with('success', 'Requisição criada com sucesso! Aguarde aprovação.');
    }

    public function show($id)
    {
        $requisicao = Requisicao::with('user', 'livro', 'livro.autores', 'livro.editora')
            ->findOrFail($id);
        
        $user = Auth::user();

        if ($user->role !== 'admin' && Auth::id() !== $requisicao->user_id) {
            return back()->with('error', 'Não autorizado a ver esta requisição.');
        }

        return view('requisicoes.show', compact('requisicao'));
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        
        if ($user->role !== 'admin') {
            return back()->with('error', 'Não autorizado');
        }

        $request->validate([
            'status' => 'required|in:aprovada,rejeitada,devolvida'
        ]);

        $requisicao = Requisicao::findOrFail($id);
   
        if ($request->status === 'devolvida' && $requisicao->status !== 'aprovada') {
            return back()->with('error', 'Apenas requisições aprovadas podem ser marcadas como devolvidas.');
        }

        if ($request->status === 'aprovada' && $requisicao->status !== 'pendente') {
            return back()->with('error', 'Apenas requisições pendentes podem ser aprovadas.');
        }

        $requisicao->update(['status' => $request->status]);

        $mensagem = match($request->status) {
            'aprovada' => 'Requisição aprovada com sucesso!',
            'rejeitada' => 'Requisição rejeitada.',
            'devolvida' => 'Livro marcado como devolvido.',
            default => 'Status atualizado!'
        };

        return back()->with('success', $mensagem);
    }

    public function destroy($id)
    {
        $requisicao = Requisicao::findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'admin' && Auth::id() !== $requisicao->user_id) {
            return back()->with('error', 'Não autorizado');
        }

        if ($requisicao->status !== 'pendente') {
            return back()->with('error', 'Apenas requisições pendentes podem ser canceladas.');
        }

        $requisicao->delete();

        return redirect()->route('requisicoes.index')
            ->with('success', 'Requisição cancelada com sucesso!');
    }

    public function verificarDisponibilidade(Request $request)
    {
        $request->validate([
            'livro_id' => 'required|exists:livros,id',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after:data_inicio',
        ]);

        $disponivel = $this->livroDisponivelPara($request->livro_id, $request->data_inicio, $request->data_fim);

        return response()->json([
            'disponivel' => $disponivel,
            'mensagem' => $disponivel ? 'Livro disponível!' : 'Livro não disponível para este período.'
        ]);
    }

    private function livroDisponivelPara($livroId, $dataInicio, $dataFim)
    {
        return !Requisicao::where('livro_id', $livroId)
            ->where('status', 'aprovada')
            ->where(function($query) use ($dataInicio, $dataFim) {
                $query->whereBetween('data_inicio', [$dataInicio, $dataFim])
                    ->orWhereBetween('data_fim', [$dataInicio, $dataFim])
                    ->orWhere(function($q) use ($dataInicio, $dataFim) {
                        $q->where('data_inicio', '<=', $dataInicio)
                          ->where('data_fim', '>=', $dataFim);
                    });
            })
            ->exists();
    }
}