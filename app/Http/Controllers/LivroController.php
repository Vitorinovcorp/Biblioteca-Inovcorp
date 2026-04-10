<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Autor;
use App\Models\Editor;
use App\Models\Requisicao;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;  
use Illuminate\Support\Facades\Log;   

class LivroController extends Controller
{
    protected $recommendationService;
    
    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }
    
    public function index()
    {
        $livros = Livro::with(['editora', 'autores'])->get();
        return view('livros', compact('livros'));
    }

    public function show($id)
    {
        $livro = Livro::with(['editora', 'autores', 'reviews' => function($query) {
            $query->where('status', 'ativo')->with('user');
        }])->findOrFail($id);
        
        $user = Auth::user();
        
        $mediaRating = $livro->reviews->avg('rating');
        $totalReviews = $livro->reviews->count();
        
        $ratingDistribution = [
            5 => $livro->reviews->where('rating', 5)->count(),
            4 => $livro->reviews->where('rating', 4)->count(),
            3 => $livro->reviews->where('rating', 3)->count(),
            2 => $livro->reviews->where('rating', 2)->count(),
            1 => $livro->reviews->where('rating', 1)->count(),
        ];
        
        $recommendations = $this->recommendationService->getCachedRelatedBooks($livro, 4);
        
        $similarityScores = [];
        foreach ($recommendations as $rec) {
            $similarityScores[$rec->id] = $this->recommendationService->calculateSimilarityScore($livro, $rec);
        }
        
        if (method_exists($livro, 'requisicoes')) {
            if ($user && $user->role === 'admin') {
                $historico = $livro->requisicoes()
                    ->with('user')
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $historico = $livro->requisicoes()
                    ->where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        } else {
            $historico = collect(); 
        }

        $disponivelAgora = $this->livroDisponivelAgora($id);
        
        return view('livros-show', compact(
            'livro', 
            'historico', 
            'disponivelAgora', 
            'mediaRating', 
            'totalReviews', 
            'ratingDistribution',
            'recommendations',
            'similarityScores'
        ));
    }
    
    public function recommendations($id)
    {
        $livro = Livro::with(['autores', 'editora'])->findOrFail($id);
        
        $recommendations = $this->recommendationService->getRelatedBooks($livro, 12);
        
        $similarityScores = [];
        foreach ($recommendations as $rec) {
            $similarityScores[$rec->id] = $this->recommendationService->calculateSimilarityScore($livro, $rec);
        }
        
        return view('livros-recommendations', compact('livro', 'recommendations', 'similarityScores'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('livros.index')
                ->with('error', 'Acesso não autorizado. Apenas administradores.');
        }

        $editoras = Editor::all();
        $autores = Autor::all();
        return view('livros-create', compact('editoras', 'autores'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('livros.index')
                ->with('error', 'Acesso não autorizado. Apenas administradores.');
        }

        $request->validate([
            'isbn' => 'required|unique:livros',
            'nome' => 'required|string|max:255',
            'bibliografia' => 'nullable|string',
            'imagem_capa' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preco' => 'required|numeric|min:0',
            'editora_id' => 'required|exists:editoras,id',
            'quantidade' => 'required|integer|min:0',
            'autores' => 'nullable|array',
            'autores.*' => 'exists:autores,id',
        ]);

        $data = $request->except('autores');

        if ($request->hasFile('imagem_capa')) {
            $path = $request->file('imagem_capa')->store('imagens/livros', 'public');
            $data['imagem_capa'] = $path;
        }

        $livro = Livro::create($data);

        if ($request->has('autores')) {
            $livro->autores()->sync($request->autores);
        }

        return redirect()->route('livros.index')
            ->with('success', 'Livro criado com sucesso!');
    }

    public function edit($id)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('livros.index')
                ->with('error', 'Acesso não autorizado. Apenas administradores.');
        }

        $livro = Livro::with('autores')->findOrFail($id);
        $editoras = Editor::all();
        $autores = Autor::all();
        
        return view('livros-edit', compact('livro', 'editoras', 'autores'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('livros.index')
                ->with('error', 'Acesso não autorizado. Apenas administradores.');
        }

        $livro = Livro::findOrFail($id);
        
        $estavaDisponivel = $this->livroDisponivelAgora($id);

        $request->validate([
            'isbn' => 'required|unique:livros,isbn,' . $id,
            'nome' => 'required|string|max:255',
            'bibliografia' => 'nullable|string',
            'imagem_capa' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preco' => 'required|numeric|min:0',
            'editora_id' => 'required|exists:editoras,id',
            'autores' => 'nullable|array',
            'autores.*' => 'exists:autores,id',
        ]);

        $data = $request->except('autores');

        if ($request->hasFile('imagem_capa')) {
            
            if ($livro->imagem_capa) {
                Storage::disk('public')->delete($livro->imagem_capa);
            }
            $path = $request->file('imagem_capa')->store('imagens/livros', 'public');
            $data['imagem_capa'] = $path;
        }

        $livro->update($data);

        if ($request->has('autores')) {
            $livro->autores()->sync($request->autores);
        }
        
        $this->recommendationService->clearCache($livro);
        
        $agoraDisponivel = $this->livroDisponivelAgora($id);
        if (!$estavaDisponivel && $agoraDisponivel && $livro->quantidade > 0) {
            $this->processAvailableBookNotifications($id);
        }

        return redirect()->route('livros.index')
            ->with('success', 'Livro atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('livros.index')
                ->with('error', 'Acesso não autorizado. Apenas administradores.');
        }

        $livro = Livro::findOrFail($id);
        
        if ($livro->imagem_capa) {
            Storage::disk('public')->delete($livro->imagem_capa);
        }
        
        $livro->delete();

        return redirect()->route('livros.index')
            ->with('success', 'Livro removido com sucesso!');
    }

    private function livroDisponivelAgora($livroId)
    {
        if (!method_exists(Livro::class, 'requisicoes')) {
            return true; 
        }

        return !Requisicao::where('livro_id', $livroId)
            ->where('status', 'aprovada')
            ->where('data_inicio', '<=', now())
            ->where('data_fim', '>=', now())
            ->exists();
    }

    public function processAvailableBookNotifications($livroId)
    {
        try {
            $livro = Livro::find($livroId);
            if (!$livro) {
                return;
            }
            
            $disponivel = $this->livroDisponivelAgora($livroId) && $livro->quantidade > 0;
            
            if ($disponivel) {
                $notifications = \App\Models\LivroNotification::where('livro_id', $livroId)
                    ->where('notificado', false)
                    ->get();
                
                foreach ($notifications as $notification) {
                    try {
                        $user = $notification->user;
                        if ($user) {
                            Mail::to($user->email)->send(new \App\Mail\LivroDisponivelMail($user, $livro));
                            
                            $notification->update([
                                'notificado' => true,
                                'notified_at' => now()
                            ]);
                            
                            Log::info('Notificação enviada para: ' . $user->email . ' sobre o livro: ' . $livro->nome);
                        }
                    } catch (\Exception $e) {
                        Log::error('Erro ao enviar notificação para ID ' . $notification->id . ': ' . $e->getMessage());
                    }
                }
                
                if ($notifications->count() > 0) {
                    Log::info('Total de notificações enviadas para o livro ' . $livro->nome . ': ' . $notifications->count());
                }
            }
        } catch (\Exception $e) {
            Log::error('Erro ao processar notificações: ' . $e->getMessage());
        }
    }
}