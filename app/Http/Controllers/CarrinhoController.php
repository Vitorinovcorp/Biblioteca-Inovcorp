<?php

namespace App\Http\Controllers;

use App\Models\Carrinho;
use App\Models\CarrinhoItem;
use App\Models\Livro;
use App\Models\Encomenda;
use App\Models\EncomendaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Mail\EncomendaConfirmadaMail;
use App\Mail\CarrinhoAbandonadoMail;

class CarrinhoController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function index()
    {
        $carrinho = $this->getCarrinho();
        return view('carrinho.index', compact('carrinho'));
    }

    public function adicionar(Request $request, $livroId)
    {
        try {
            $livro = Livro::findOrFail($livroId);
            
            if ($livro->quantidade <= 0) {
                return redirect()->back()->with('mensagem', '❌ Este livro está indisponível no momento.');
            }

            $carrinho = $this->getCarrinho();
            
            $item = $carrinho->itens()->where('livro_id', $livroId)->first();
            
            if ($item) {
                if ($item->quantidade + 1 > $livro->quantidade) {
                    return redirect()->back()->with('mensagem', '⚠️ Quantidade máxima disponível em estoque.');
                }
                $item->increment('quantidade');
                $mensagem = '✅ +1 ' . $livro->nome . ' (Agora você tem ' . $item->quantidade . 'x)';
            } else {
                CarrinhoItem::create([
                    'carrinho_id' => $carrinho->id,
                    'livro_id' => $livroId,
                    'quantidade' => 1,
                    'preco_unitario' => $livro->preco,
                ]);
                $mensagem = '✅ ' . $livro->nome . ' adicionado ao carrinho!';
            }

            $totalItens = $carrinho->fresh()->itens->sum('quantidade');
            session(['carrinho_total_itens' => $totalItens]);

            return redirect()->back()->with('mensagem', $mensagem);
            
        } catch (\Exception $e) {
            Log::error('Erro ao adicionar ao carrinho: ' . $e->getMessage());
            return redirect()->back()->with('mensagem', '❌ Erro ao adicionar ao carrinho. Tente novamente.');
        }
    }

    public function adicionarAjax(Request $request, $livroId)
{
    try {
        $livro = Livro::findOrFail($livroId);
        
        if ($livro->quantidade <= 0) {
            return response()->json([
                'success' => false,
                'message' => '❌ Este livro está indisponível no momento.'
            ], 400);
        }

        $carrinho = $this->getCarrinho();
        
        $item = $carrinho->itens()->where('livro_id', $livroId)->first();
        
        if ($item) {
            if ($item->quantidade + 1 > $livro->quantidade) {
                return response()->json([
                    'success' => false,
                    'message' => '⚠️ Quantidade máxima disponível em estoque.'
                ], 400);
            }
            $item->increment('quantidade');
            $mensagem = '✅ +1 ' . $livro->nome . ' (Agora você tem ' . $item->quantidade . 'x)';
            $novaQuantidade = $item->quantidade;
            $acao = 'incrementou';
        } else {
            CarrinhoItem::create([
                'carrinho_id' => $carrinho->id,
                'livro_id' => $livroId,
                'quantidade' => 1,
                'preco_unitario' => $livro->preco,
            ]);
            $mensagem = '✅ ' . $livro->nome . ' adicionado ao carrinho!';
            $novaQuantidade = 1;
            $acao = 'adicionou';
        }

        $totalItens = $carrinho->fresh()->itens->sum('quantidade');
        session(['carrinho_total_itens' => $totalItens]);

        return response()->json([
            'success' => true,
            'message' => $mensagem,
            'total_itens' => $totalItens,
            'nova_quantidade' => $novaQuantidade,
            'acao' => $acao,
            'item_id' => $item ? $item->id : null
        ]);
        
    } catch (\Exception $e) {
        Log::error('Erro ao adicionar ao carrinho (AJAX): ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => '❌ Erro ao adicionar ao carrinho. Tente novamente.'
        ], 500);
    }
}

    public function atualizar(Request $request, $itemId)
    {
        $request->validate([
            'quantidade' => 'required|integer|min:1'
        ]);

        $item = CarrinhoItem::findOrFail($itemId);
        $carrinho = $this->getCarrinho();
        
        if ($item->carrinho_id != $carrinho->id) {
            abort(403);
        }

        $livro = Livro::find($item->livro_id);
        
        if ($request->quantidade > $livro->quantidade) {
            return redirect()->route('carrinho.index')->with('mensagem', '⚠️ Quantidade máxima disponível em estoque.');
        }

        $item->update(['quantidade' => $request->quantidade]);

        $totalItens = $carrinho->fresh()->itens->sum('quantidade');
        session(['carrinho_total_itens' => $totalItens]);

        return redirect()->route('carrinho.index')->with('mensagem', '🛒 Carrinho atualizado com sucesso!');
    }

    public function remover($itemId)
    {
        $item = CarrinhoItem::findOrFail($itemId);
        $carrinho = $this->getCarrinho();
        
        if ($item->carrinho_id != $carrinho->id) {
            abort(403);
        }

        $livroNome = $item->livro->nome;
        $item->delete();

        $totalItens = $carrinho->fresh()->itens->sum('quantidade');
        session(['carrinho_total_itens' => $totalItens]);

        return redirect()->route('carrinho.index')->with('mensagem', '🗑️ "' . $livroNome . '" removido do carrinho!');
    }

    public function checkout()
    {
        $carrinho = $this->getCarrinho();
        
        if ($carrinho->itens->isEmpty()) {
            return redirect()->route('carrinho.index')->with('mensagem', '🛒 Seu carrinho está vazio.');
        }

        foreach ($carrinho->itens as $item) {
            if ($item->quantidade > $item->livro->quantidade) {
                return redirect()->route('carrinho.index')->with('mensagem', "⚠️ O livro '{$item->livro->nome}' não tem quantidade suficiente em estoque.");
            }
        }

        return view('carrinho.checkout', compact('carrinho'));
    }

    public function processarCheckout(Request $request)
    {
        $request->validate([
            'morada_entrega' => 'required|string|max:500',
            'codigo_postal' => 'required|string|max:20',
            'cidade' => 'required|string|max:100',
            'telefone' => 'nullable|string|max:20',
        ]);

        $carrinho = $this->getCarrinho();
        
        if ($carrinho->itens->isEmpty()) {
            return redirect()->route('carrinho.index')->with('mensagem', '🛒 Seu carrinho está vazio.');
        }

        foreach ($carrinho->itens as $item) {
            if ($item->quantidade > $item->livro->quantidade) {
                return back()->with('mensagem', "⚠️ O livro '{$item->livro->nome}' não tem quantidade suficiente.");
            }
        }

        $encomenda = Encomenda::create([
            'user_id' => Auth::id(),
            'numero_encomenda' => Encomenda::gerarNumeroEncomenda(),
            'total' => $carrinho->total,
            'status_pagamento' => 'pendente',
            'morada_entrega' => $request->morada_entrega,
            'codigo_postal' => $request->codigo_postal,
            'cidade' => $request->cidade,
            'telefone' => $request->telefone,
        ]);

        foreach ($carrinho->itens as $item) {
            EncomendaItem::create([
                'encomenda_id' => $encomenda->id,
                'livro_id' => $item->livro_id,
                'quantidade' => $item->quantidade,
                'preco_unitario' => $item->preco_unitario,
            ]);
        }

        $carrinho->itens()->delete();
        $carrinho->update(['status' => 'finalizado']);
        session(['carrinho_total_itens' => 0]);

        return redirect()->route('carrinho.pagamento', $encomenda->id);
    }

    public function mostrarPagamento($encomendaId)
    {
        $encomenda = Encomenda::with('itens.livro')->findOrFail($encomendaId);
        
        if ($encomenda->user_id != Auth::id()) {
            abort(403);
        }
        
        return view('carrinho.pagamento', compact('encomenda'));
    }

    public function processarPagamento(Request $request)
    {
        try {
            $request->validate([
                'payment_method_id' => 'required|string',
                'encomenda_id' => 'required|exists:encomendas,id'
            ]);
            
            $encomenda = Encomenda::findOrFail($request->encomenda_id);
            
            if ($encomenda->user_id != auth()->id()) {
                return response()->json(['success' => false, 'error' => 'Encomenda não encontrada.'], 403);
            }
            
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => round($encomenda->total * 100),
                'currency' => 'eur',
                'payment_method' => $request->payment_method_id,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => route('carrinho.sucesso', $encomenda->id),
                'metadata' => [
                'encomenda_id' => $encomenda->id,
                'user_id' => auth()->id(),
                ],
            ]);
            
            if ($paymentIntent->status === 'succeeded') {
                $encomenda->update([
                    'status_pagamento' => 'pago',
                    'pago_em' => now(),
                    'stripe_payment_intent_id' => $paymentIntent->id,
                ]);
                
                foreach ($encomenda->itens as $item) {
                    $item->livro->decrement('quantidade', $item->quantidade);
                }
                
                try {
                    Mail::to($encomenda->user->email)->send(new EncomendaConfirmadaMail($encomenda));
                } catch (\Exception $e) {
                    Log::error('Erro ao enviar email: ' . $e->getMessage());
                }
                
                return response()->json(['success' => true]);
            }
            
            return response()->json(['success' => false, 'error' => 'Pagamento não confirmado.']);
            
        } catch (\Exception $e) {
            Log::error('Erro no pagamento: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function sucesso($encomendaId, Request $request)
    {
        $encomenda = Encomenda::findOrFail($encomendaId);
        
        if ($encomenda->user_id != Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('carrinho.sucesso', compact('encomenda'));
    }

    public function cancelar($encomendaId)
    {
        $encomenda = Encomenda::findOrFail($encomendaId);
        
        if ($encomenda->user_id != Auth::id()) {
            abort(403);
        }

        $encomenda->update(['status_pagamento' => 'falhou']);

        return redirect()->route('carrinho.index')->with('mensagem', 'Pagamento cancelado.');
    }

    public function getTotalItens()
    {
        $carrinho = $this->getCarrinho();
        return response()->json([
            'total_itens' => $carrinho->itens->sum('quantidade')
        ]);
    }

    private function getCarrinho()
    {
        $user = Auth::user();
        
        $carrinho = Carrinho::where('user_id', $user->id)
            ->where('status', 'aberto')
            ->first();
        
        if (!$carrinho) {
            $carrinho = Carrinho::create([
                'user_id' => $user->id,
                'session_id' => null, 
                'status' => 'aberto',
            ]);
        }
        
        return $carrinho;
    }
}