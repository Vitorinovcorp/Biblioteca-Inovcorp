<?php

namespace App\Http\Controllers;

use App\Models\Requisicao;
use App\Models\Livro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        $user = Auth::user();

        // Verificar se o usuário tem foto cadastrada
        // No método create() do RequisicaoController
        if (!$user->foto) {
            return redirect()->route('requisicoes.index') 
                ->with('error', 'Você precisa cadastrar uma foto para fazer requisições. Contate o administrador.');
        }

        $livrosDisponiveis = Livro::with('autores', 'editora')
            ->whereDoesntHave('requisicoes', function ($query) {
                $query->where('status', 'aprovada')
                    ->where('data_inicio', '<=', now())
                    ->where('data_fim', '>=', now());
            })
            ->get();

        return view('requisicoes.create', compact('livrosDisponiveis'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->foto) {
            return back()
                ->with('error', 'Você precisa cadastrar uma foto para fazer requisições.')
                ->withInput();
        }

        $livrosAtivos = Requisicao::where('user_id', Auth::id())
            ->where('status', 'aprovada')
            ->where('data_fim', '>=', now())
            ->count();

        if ($livrosAtivos >= 3) {
            return back()
                ->with('error', 'Você já atingiu o limite de 3 livros requisitados em simultâneo. Devolva algum livro para fazer nova requisição.')
                ->withInput();
        }

        $request->validate([
            'livro_id' => 'required|exists:livros,id',
            'data_inicio' => 'required|date|after_or_equal:today',
            'observacoes' => 'nullable|string|max:500',
        ]);

        $livro = Livro::findOrFail($request->livro_id);

        // Calcular data fim = data início + 5 dias (Regra 1)
        $dataFim = Carbon::parse($request->data_inicio)->addDays(5);

        if (!$this->livroDisponivelPara($request->livro_id, $request->data_inicio, $dataFim)) {
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
            'data_fim' => $dataFim, // Automaticamente 5 dias depois
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

        $mensagem = match ($request->status) {
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
        ]);

        $dataFim = Carbon::parse($request->data_inicio)->addDays(5);

        $disponivel = $this->livroDisponivelPara($request->livro_id, $request->data_inicio, $dataFim);

        return response()->json([
            'disponivel' => $disponivel,
            'mensagem' => $disponivel ? 'Livro disponível!' : 'Livro não disponível para este período.'
        ]);
    }

    private function livroDisponivelPara($livroId, $dataInicio, $dataFim)
    {
        return !Requisicao::where('livro_id', $livroId)
            ->where('status', 'aprovada')
            ->where(function ($query) use ($dataInicio, $dataFim) {
                $query->whereBetween('data_inicio', [$dataInicio, $dataFim])
                    ->orWhereBetween('data_fim', [$dataInicio, $dataFim])
                    ->orWhere(function ($q) use ($dataInicio, $dataFim) {
                        $q->where('data_inicio', '<=', $dataInicio)
                            ->where('data_fim', '>=', $dataFim);
                    });
            })
            ->exists();
    }
}
