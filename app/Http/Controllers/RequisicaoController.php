<?php

namespace App\Http\Controllers;

use App\Models\Requisicao;
use App\Models\Livro;
use App\Models\User;
use App\Mail\NovaRequisicaoMail;
use App\Mail\DevolucaoConfirmadaMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
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

    if ($user->role === 'admin') {
        $requisicoesAtivas = Requisicao::where('status', 'aprovada')
            ->where('data_fim', '>=', now())
            ->count();

        $requisicoes30Dias = Requisicao::where('created_at', '>=', now()->subDays(30))
            ->count();

        $livrosEntreguesHoje = Requisicao::where('status', 'devolvida')
            ->whereDate('data_devolucao_real', today())
            ->count();
    } else {
        $requisicoesAtivas = Requisicao::where('user_id', Auth::id())
            ->where('status', 'aprovada')
            ->where('data_fim', '>=', now())
            ->count();
        $requisicoes30Dias = Requisicao::where('user_id', Auth::id())
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        $livrosEntreguesHoje = Requisicao::where('user_id', Auth::id())
            ->where('status', 'devolvida')
            ->whereDate('data_devolucao_real', today())
            ->count();
    }

    return view('requisicoes.index', compact(
        'requisicoes',
        'requisicoesAtivas',
        'requisicoes30Dias',
        'livrosEntreguesHoje'
    ));
}
    public function create()
{
    $user = Auth::user();

    if (!$user->foto) {
        return redirect()->route('requisicoes.index')
            ->with('error', 'Você precisa cadastrar uma foto para fazer requisições. Atualize seu perfil.');
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


    public function showDevolucaoForm($id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return back()->with('error', 'Não autorizado');
        }

        $requisicao = Requisicao::with('user', 'livro')->findOrFail($id);

        if ($requisicao->status !== 'aprovada') {
            return redirect()->route('requisicoes.index')
                ->with('error', 'Apenas requisições aprovadas podem ser devolvidas.');
        }

        return view('requisicoes.devolver', compact('requisicao'));
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

        $requisicao = Requisicao::create([
            'user_id' => Auth::id(),
            'livro_id' => $request->livro_id,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $dataFim,
            'observacoes' => $request->observacoes,
            'status' => 'pendente',
        ]);

        // Enviar email para o cidadão
        try {
            Mail::to($user->email)->send(new NovaRequisicaoMail($requisicao, 'cidadão'));

            // Buscar todos os usuários com role 'admin'
            $admins = User::where('role', 'admin')->get();

            if ($admins->count() > 0) {
                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(new NovaRequisicaoMail($requisicao, 'admin'));
                }
                Log::info('Emails enviados para ' . $admins->count() . ' administradores');
            } else {
                Log::warning('Nenhum administrador encontrado para enviar notificação');
            }
        } catch (\Exception $e) {
            // Log do erro mas não interrompe o fluxo
            Log::error('Erro ao enviar email: ' . $e->getMessage());
        }

        return redirect()->route('requisicoes.index')
            ->with('success', 'Requisição criada com sucesso! Aguarde aprovação. Um email de confirmação foi enviado.');
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

    public function confirmarDevolucao(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return back()->with('error', 'Não autorizado');
        }

        $request->validate([
            'data_devolucao_real' => 'required|date',
            'observacoes_devolucao' => 'nullable|string|max:500',
        ]);

        $requisicao = Requisicao::findOrFail($id);

        if ($requisicao->status !== 'aprovada') {
            return back()->with('error', 'Apenas requisições aprovadas podem ser devolvidas.');
        }

        $dataDevolucao = Carbon::parse($request->data_devolucao_real);
        $dataFimPrevista = Carbon::parse($requisicao->data_fim);

        // Calcular dias de atraso
        $diasAtraso = 0;
        if ($dataDevolucao->gt($dataFimPrevista)) {
            $diasAtraso = $dataDevolucao->diffInDays($dataFimPrevista);
        }

        $requisicao->update([
            'status' => 'devolvida',
            'data_devolucao_real' => $request->data_devolucao_real,
            'dias_atraso' => $diasAtraso,
            'observacoes_devolucao' => $request->observacoes_devolucao,
        ]);

        // Enviar email de confirmação de devolução
        try {
            Mail::to($requisicao->user->email)->send(new DevolucaoConfirmadaMail($requisicao));

            // Opcional: notificar admins sobre a devolução
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new DevolucaoConfirmadaMail($requisicao));
            }
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de devolução: ' . $e->getMessage());
        }

        return redirect()->route('requisicoes.index')
            ->with('success', 'Devolução confirmada com sucesso! ' . ($diasAtraso > 0 ? "Dias em atraso: $diasAtraso" : ''));
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
