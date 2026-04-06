<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\LivroNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\LivroDisponivelMail;

class LivroNotificationController extends Controller
{
    public function subscribe(Request $request, $livroId)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você precisa estar logado para solicitar notificação.'
                ], 401);
            }
            
            $livro = Livro::findOrFail($livroId);
            
            if ($this->livroDisponivel($livro)) {
                return response()->json([
                    'success' => false,
                    'message' => 'O livro está disponível no momento. Você pode requisitá-lo diretamente.'
                ], 400);
            }
            
            $existing = LivroNotification::where('user_id', $user->id)
                ->where('livro_id', $livroId)
                ->where('notificado', false)
                ->first();
            
            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você já está inscrito para receber notificação quando este livro ficar disponível.'
                ], 400);
            }
            
            $notification = LivroNotification::create([
                'user_id' => $user->id,
                'livro_id' => $livroId,
                'email' => $user->email,
                'notificado' => false,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Você receberá um email assim que o livro ficar disponível!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao solicitar notificação: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function unsubscribe(Request $request, $livroId)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado.'
                ], 401);
            }
            
            $notification = LivroNotification::where('user_id', $user->id)
                ->where('livro_id', $livroId)
                ->where('notificado', false)
                ->first();
            
            if ($notification) {
                $notification->delete();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Inscrição cancelada com sucesso.'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Você não está inscrito para este livro.'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao cancelar inscrição.'
            ], 500);
        }
    }
    
    public function checkSubscription($livroId)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'is_subscribed' => false,
                    'is_available' => false
                ]);
            }
            
            $livro = Livro::findOrFail($livroId);
            
            $isSubscribed = LivroNotification::where('user_id', $user->id)
                ->where('livro_id', $livroId)
                ->where('notificado', false)
                ->exists();
            
            $isAvailable = $this->livroDisponivel($livro);
            
            return response()->json([
                'is_subscribed' => $isSubscribed,
                'is_available' => $isAvailable
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'is_subscribed' => false,
                'is_available' => false
            ]);
        }
    }
    
    private function livroDisponivel($livro)
    {
        if ($livro->quantidade <= 0) {
            return false;
        }
        
        $temEmprestimoAtivo = \App\Models\Requisicao::where('livro_id', $livro->id)
            ->where('status', 'aprovada')
            ->where('data_inicio', '<=', now())
            ->where('data_fim', '>=', now())
            ->exists();
        
        return !$temEmprestimoAtivo;
    }
}