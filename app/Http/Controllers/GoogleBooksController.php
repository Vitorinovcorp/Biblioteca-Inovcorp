<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Livro;
use App\Models\Editor;
use App\Models\Autor;

class GoogleBooksController extends Controller
{
    public function index()
    {
        $editoras = Editor::all();
        $autores = Autor::all();
        return view('google-books.search', compact('editoras', 'autores'));
    }
  
    public function search(Request $request)
    {
        // Log para debug
        Log::info('=== PESQUISA RECEBIDA ===');
        Log::info('Query: ' . $request->q);
        
        $request->validate([
            'q' => 'required|string|min:2',
        ]);
        
        try {
            $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
                'q' => $request->q,
                'maxResults' => 20
            ]);
            
            $results = $response->successful() ? $response->json() : ['items' => []];
            Log::info('Livros encontrados: ' . count($results['items'] ?? []));
            
        } catch (\Exception $e) {
            Log::error('Erro na busca: ' . $e->getMessage());
            $results = ['items' => []];
        }
        
        $editoras = Editor::all();
        $autores = Autor::all();
        
        return view('google-books.search', [
            'results' => $results,
            'query' => $request->q,
            'editoras' => $editoras,
            'autores' => $autores
        ]);
    }
    
    public function import(Request $request)
    {
        try {
            if (!Auth::user() || Auth::user()->role !== 'admin') {
                return response()->json([
                    'error' => 'Apenas administradores podem importar livros'
                ], 403);
            }
            
            $request->validate([
                'volume_id' => 'required',
                'nome' => 'required|max:255',
                'preco' => 'required|numeric|min:0',
                'editora_id' => 'required|exists:editoras,id',
                'isbn' => 'nullable|unique:livros,isbn',
                'bibliografia' => 'nullable',
                'autores' => 'nullable|array'
            ]);
           
            $response = Http::get("https://www.googleapis.com/books/v1/volumes/{$request->volume_id}");
            
            if (!$response->successful()) {
                return response()->json(['error' => 'Livro não encontrado na API do Google'], 404);
            }
            
            $book = $response->json();
            
            $data = [
                'nome' => $request->nome,
                'isbn' => $request->isbn,
                'preco' => $request->preco,
                'bibliografia' => $request->bibliografia,
                'editora_id' => $request->editora_id,
                'quantidade' => 1,
                'external_id' => $book['id'],
                'user_id' => Auth::id()
            ];
            
            $imageUrl = $book['volumeInfo']['imageLinks']['thumbnail'] ?? 
                        $book['volumeInfo']['imageLinks']['smallThumbnail'] ?? null;
            
            if ($imageUrl) {
                try {
                    $imageContent = Http::get($imageUrl)->body();
                    $imageName = 'google_' . $book['id'] . '.jpg';
                    $imagePath = 'imagens/livros/' . $imageName;
                    
                    Storage::disk('public')->put($imagePath, $imageContent);
                    $data['imagem_capa'] = $imagePath;
                } catch (\Exception $e) {
                    Log::error('Erro ao baixar imagem: ' . $e->getMessage());
                }
            }
            
            $livro = Livro::create($data);
            
            if ($request->has('autores') && !empty($request->autores)) {
                $livro->autores()->sync($request->autores);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Livro importado com sucesso!',
                'redirect' => route('livros.show', $livro->id)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao importar: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erro ao importar livro: ' . $e->getMessage()
            ], 500);
        }
    }
}
