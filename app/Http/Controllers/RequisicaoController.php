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
        if (Auth::user()->role === 'admin') {
            $requisicoes = Requisicao::with('user', 'livro')->latest()->get();
        } else {
            $requisicoes = Requisicao::where('user_id', Auth::id())
                ->with('livro')
                ->latest()
                ->get();
        }
        
        return view('requisicoes-index', compact('requisicoes'));
    }

    public function create()
    {
        $livros = Livro::all();
        return view('requisicoes-create', compact('livros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'livro_id' => 'required|exists:livros,id',
            'data_inicio' => 'required|date|after_or_equal:today',
            'data_fim' => 'required|date|after:data_inicio',
            'observacoes' => 'nullable|string|max:500',
        ]);

        // Verificar disponibilidade
        $livroRequisitado = Requisicao::where('livro_id', $request->livro_id)
            ->where('status', 'aprovada')
            ->where(function($query) use ($request) {
                $query->whereBetween('data_inicio', [$request->data_inicio, $request->data_fim])
                    ->orWhereBetween('data_fim', [$request->data_inicio, $request->data_fim])
                    ->orWhere(function($q) use ($request) {
                        $q->where('data_inicio', '<=', $request->data_inicio)
                          ->where('data_fim', '>=', $request->data_fim);
                    });
            })
            ->exists();

        if ($livroRequisitado) {
            return back()->with('error', 'Este livro já está requisitado para o período selecionado.')
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

    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'Não autorizado');
        }

        $request->validate([
            'status' => 'required|in:aprovada,rejeitada,devolvida'
        ]);

        $requisicao = Requisicao::findOrFail($id);
        $requisicao->update(['status' => $request->status]);

        return back()->with('success', 'Status da requisição atualizado!');
    }

    public function destroy($id)
    {
        $requisicao = Requisicao::findOrFail($id);

        // Apenas o dono ou admin pode cancelar
        if (Auth::id() !== $requisicao->user_id && Auth::user()->role !== 'admin') {
            return back()->with('error', 'Não autorizado');
        }

        // Só pode cancelar se estiver pendente
        if ($requisicao->status !== 'pendente') {
            return back()->with('error', 'Apenas requisições pendentes podem ser canceladas.');
        }

        $requisicao->delete();

        return redirect()->route('requisicoes.index')
            ->with('success', 'Requisição cancelada com sucesso!');
    }
}