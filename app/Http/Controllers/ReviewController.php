<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Requisicao;
use App\Models\User;
use App\Mail\NewReviewMail;
use App\Mail\ReviewStatusMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function pendingReviews()
    {
        $this->authorizeAdmin();

        $reviews = Review::with(['user', 'livro', 'requisicao'])
            ->where('status', 'suspenso')
            ->latest()
            ->paginate(15);

        return view('reviews.pending', compact('reviews'));
    }

    public function index()
    {
        $this->authorizeAdmin();

        $reviews = Review::with(['user', 'livro', 'requisicao'])
            ->latest()
            ->paginate(20);

        return view('reviews.index', compact('reviews'));
    }

    public function show($id)
    {
        $review = Review::with(['user', 'livro', 'requisicao'])->findOrFail($id);
        
        $user = Auth::user();
        
        if ($user->role !== 'admin') {
            if ($review->user_id !== $user->id) {
                abort(403, 'Acesso não autorizado');
            }
        }

        return view('reviews.show', compact('review'));
    }

    public function store(Request $request, $requisicaoId)
    {
        try {
            Log::info('Iniciando criação de review', ['requisicao_id' => $requisicaoId, 'user_id' => Auth::id()]);
            
            $user = Auth::user();
            
            if (!$user) {
                Log::error('Usuário não autenticado');
                return response()->json(['error' => 'Usuário não autenticado'], 401);
            }
            
            $requisicao = Requisicao::with('livro')->find($requisicaoId);
            
            if (!$requisicao) {
                Log::error('Requisição não encontrada', ['requisicao_id' => $requisicaoId]);
                return response()->json(['error' => 'Requisição não encontrada'], 404);
            }
            
            if ($requisicao->user_id !== $user->id) {
                Log::error('Usuário não autorizado', ['user_id' => $user->id, 'requisicao_user_id' => $requisicao->user_id]);
                return response()->json(['error' => 'Não autorizado'], 403);
            }
            
            if ($requisicao->status !== 'devolvida') {
                Log::error('Requisição não está devolvida', ['status' => $requisicao->status]);
                return response()->json(['error' => 'Você só pode avaliar livros que já foram devolvidos'], 400);
            }
            
            $existingReview = Review::where('requisicao_id', $requisicaoId)->first();
            if ($existingReview) {
                Log::error('Review já existe', ['review_id' => $existingReview->id]);
                return response()->json(['error' => 'Você já fez uma review para esta requisição'], 400);
            }
            
            $validated = $request->validate([
                'review' => 'required|string|min:10|max:1000',
                'rating' => 'nullable|integer|min:1|max:5',
            ]);
            
            Log::info('Dados validados', ['review' => $validated['review'], 'rating' => $validated['rating'] ?? null]);
            
            $review = Review::create([
                'requisicao_id' => $requisicaoId,
                'user_id' => $user->id,
                'livro_id' => $requisicao->livro_id,
                'review' => $validated['review'],
                'rating' => $validated['rating'] ?? null,
                'status' => 'suspenso',
            ]);
            
            Log::info('Review criada com sucesso', ['review_id' => $review->id]);
            
            $admins = User::where('role', 'admin')->get();
            
            if ($admins->count() > 0) {
                foreach ($admins as $admin) {
                    try {
                        Mail::to($admin->email)->send(new NewReviewMail($review));
                        Log::info('Email enviado para admin', ['admin_email' => $admin->email]);
                    } catch (\Exception $e) {
                        Log::error("Erro ao enviar email para admin {$admin->email}: " . $e->getMessage());
                    }
                }
            } else {
                Log::warning('Nenhum administrador encontrado para enviar notificação');
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Review enviada com sucesso! Aguarde a moderação.',
                'review' => $review
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erro de validação', ['errors' => $e->errors()]);
            return response()->json(['error' => 'Dados inválidos: ' . json_encode($e->errors())], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao salvar review: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Erro ao salvar review: ' . $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $this->authorizeAdmin();

            $request->validate([
                'status' => 'required|in:ativo,recusado',
                'justificativa' => 'required_if:status,recusado|nullable|string|max:500',
            ]);

            $review = Review::findOrFail($id);
            $oldStatus = $review->status;
            
            $review->status = $request->status;
            
            if ($request->status === 'recusado' && $request->justificativa) {
                $review->justificativa_recusa = $request->justificativa;
            }
            
            $review->save();

            Log::info('Review status atualizado', ['review_id' => $id, 'old_status' => $oldStatus, 'new_status' => $request->status]);

            try {
                Mail::to($review->user->email)->send(new ReviewStatusMail(
                    $review, 
                    $request->status, 
                    $request->justificativa ?? null
                ));
                Log::info('Email de status enviado para o cidadão', ['email' => $review->user->email]);
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de status: ' . $e->getMessage());
            }

            $message = $request->status === 'ativo' 
                ? 'Review aprovada e publicada com sucesso!' 
                : 'Review recusada. O usuário foi notificado.';

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $message]);
            }

            return redirect()->route('reviews.show', $id)
                ->with('success', $message);
                
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar status da review: ' . $e->getMessage());
            return back()->with('error', 'Erro ao atualizar status: ' . $e->getMessage());
        }
    }

    public function livroReviews($livroId)
    {
        try {
            $reviews = Review::with('user')
                ->where('livro_id', $livroId)
                ->where('status', 'ativo')
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'reviews' => $reviews
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar reviews do livro: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar reviews'], 500);
        }
    }

    public function checkReview($requisicaoId)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'Usuário não autenticado'], 401);
            }
            
            $review = Review::where('requisicao_id', $requisicaoId)
                ->where('user_id', $user->id)
                ->first();

            return response()->json([
                'has_review' => !is_null($review),
                'review' => $review,
                'status' => $review ? $review->status : null
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao verificar review: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao verificar review'], 500);
        }
    }

    private function authorizeAdmin()
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403, 'Acesso não autorizado. Apenas administradores podem acessar esta página.');
        }
    }
}