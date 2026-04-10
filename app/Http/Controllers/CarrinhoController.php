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
                return redirect()->back()->with('error', 'Este livro está indisponível no momento.');
            }

            $carrinho = $this->getCarrinho();
            
            $item = $carrinho->itens()->where('livro_id', $livroId)->first();
            
            if ($item) {
                if ($item->quantidade + 1 > $livro->quantidade) {
                    return redirect()->back();
                }
                $item->increment('quantidade');
            } else {
                CarrinhoItem::create([
                    'carrinho_id' => $carrinho->id,
                    'livro_id' => $livroId,
                    'quantidade' => 1,
                    'preco_unitario' => $livro->preco,
                ]);
            }

            // Atualizar a sessão com o novo total de itens
            $totalItens = $carrinho->fresh()->itens->sum('quantidade');
            session(['carrinho_total_itens' => $totalItens]);

            return redirect()->back()->with('success', 'Livro adicionado ao carrinho!');
            
        } catch (\Exception $e) {
            Log::error('Erro ao adicionar ao carrinho: ' . $e->getMessage());
            return redirect()->back();
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
            return back()->with('error', 'Quantidade máxima disponível em estoque.');
        }

        $item->update(['quantidade' => $request->quantidade]);

        // Atualizar a sessão com o novo total de itens
        $totalItens = $carrinho->fresh()->itens->sum('quantidade');
        session(['carrinho_total_itens' => $totalItens]);

        return redirect()->route('carrinho.index')->with('success', 'Carrinho atualizado!');
    }

    public function remover($itemId)
    {
        $item = CarrinhoItem::findOrFail($itemId);
        $carrinho = $this->getCarrinho();
        
        if ($item->carrinho_id != $carrinho->id) {
            abort(403);
        }

        $item->delete();

        // Atualizar a sessão com o novo total de itens
        $totalItens = $carrinho->fresh()->itens->sum('quantidade');
        session(['carrinho_total_itens' => $totalItens]);

        return redirect()->route('carrinho.index')->with('success', 'Item removido do carrinho!');
    }

    public function checkout()
    {
        $carrinho = $this->getCarrinho();
        
        if ($carrinho->itens->isEmpty()) {
            return redirect()->route('carrinho.index')->with('error', 'Seu carrinho está vazio.');
        }

        foreach ($carrinho->itens as $item) {
            if ($item->quantidade > $item->livro->quantidade) {
                return redirect()->route('carrinho.index')->with('error', 
                    "O livro '{$item->livro->nome}' não tem quantidade suficiente em estoque.");
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
            return redirect()->route('carrinho.index')->with('error', 'Seu carrinho está vazio.');
        }

        foreach ($carrinho->itens as $item) {
            if ($item->quantidade > $item->livro->quantidade) {
                return back()->with('error', "O livro '{$item->livro->nome}' não tem quantidade suficiente.");
            }
        }

        // Criar encomenda
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

        try {
            $lineItems = [];
            foreach ($carrinho->itens as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $item->livro->nome,
                            'description' => $item->livro->autores->pluck('nome')->implode(', ') ?: 'Autor não informado',
                        ],
                        'unit_amount' => round($item->preco_unitario * 100),
                    ],
                    'quantity' => $item->quantidade,
                ];
            }

            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('carrinho.sucesso', $encomenda->id) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('carrinho.cancelar', $encomenda->id),
                'metadata' => [
                    'encomenda_id' => $encomenda->id,
                    'user_id' => Auth::id(),
                ],
            ]);

            $encomenda->update([
                'stripe_session_id' => $checkoutSession->id,
                'stripe_payment_intent_id' => $checkoutSession->payment_intent,
            ]);

            // Limpar carrinho
            $carrinho->itens()->delete();
            $carrinho->update(['status' => 'finalizado']);
            
            // Limpar a sessão
            session(['carrinho_total_itens' => 0]);

            return redirect($checkoutSession->url);
            
        } catch (\Exception $e) {
            Log::error('Erro no checkout: ' . $e->getMessage());
            return redirect()->route('carrinho.index')->with('error', 'Erro ao processar pagamento. Tente novamente.');
        }
    }

    public function sucesso($encomendaId, Request $request)
    {
        $encomenda = Encomenda::findOrFail($encomendaId);
        
        if ($encomenda->user_id != Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($request->session_id) {
            try {
                $session = Session::retrieve($request->session_id);
                
                if ($session->payment_status === 'paid') {
                    $encomenda->update([
                        'status_pagamento' => 'pago',
                        'pago_em' => now(),
                    ]);

                    foreach ($encomenda->itens as $item) {
                        $livro = $item->livro;
                        $livro->decrement('quantidade', $item->quantidade);
                    }

                    try {
                        Mail::to($encomenda->user->email)->send(new EncomendaConfirmadaMail($encomenda));
                    } catch (\Exception $e) {
                        Log::error('Erro ao enviar email de confirmação: ' . $e->getMessage());
                    }
                }
            } catch (\Exception $e) {
                Log::error('Erro ao verificar pagamento: ' . $e->getMessage());
            }
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

        return redirect()->route('carrinho.index')->with('error', 'Pagamento cancelado.');
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