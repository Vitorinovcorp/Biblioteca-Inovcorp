<?php

namespace App\Http\Controllers;

use App\Services\GoogleBooksService;
use App\Models\Livro;
use App\Models\Autor;
use App\Models\Editor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GoogleBooksController extends Controller
{
    protected $googleBooks;
    
    public function __construct(GoogleBooksService $googleBooks)
    {
        $this->googleBooks = $googleBooks;
    }

    public function index()
    {
        return view('google-books.search');
    }
  
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
            'maxResults' => 'integer|min:1|max:40'
        ]);
        
        $results = $this->googleBooks->search(
            $request->q,
            $request->get('maxResults', 20),
            $request->get('startIndex', 0)
        );
        
        return view('google-books.results', [
            'results' => $results,
            'query' => $request->q
        ]);
    }
    
    public function showImportForm($volumeId)
    {
        $volume = $this->googleBooks->getVolume($volumeId);
        
        if (!$volume) {
            return redirect()->route('google-books.search')
                ->with('error', 'Livro não encontrado na Google Books');
        }
        
        $mappedData = $this->googleBooks->mapToLivroData($volume);
        
        $existe = Livro::where('external_id', $volume['id'])
                    ->orWhere('isbn', $mappedData['isbn'])
                    ->exists();
        
        $editoras = Editor::all();
        $autores = Autor::all();
        
        return view('google-books.import', [
            'googleBook' => $volume,
            'mappedData' => $mappedData,
            'existe' => $existe,
            'editoras' => $editoras,
            'autores' => $autores
        ]);
    }
    
    public function import(Request $request, $volumeId)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('livros.index')
                ->with('error', 'Acesso não autorizado. Apenas administradores.');
        }
        
        $volume = $this->googleBooks->getVolume($volumeId);
        
        if (!$volume) {
            return redirect()->route('google-books.search')
                ->with('error', 'Livro não encontrado');
        }
        
        $request->validate([
            'isbn' => 'nullable|unique:livros,isbn',
            'nome' => 'required|string|max:255',
            'bibliografia' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'editora_id' => 'required|exists:editoras,id',
            'autores' => 'nullable|array',
            'autores.*' => 'exists:autores,id',
        ]);
        
        $data = [
            'external_id' => $volume['id'],
            'isbn' => $request->isbn,
            'nome' => $request->nome,
            'bibliografia' => $request->bibliografia,
            'preco' => $request->preco,
            'editora_id' => $request->editora_id,
        ];
        
        $imageUrl = $volume['volumeInfo']['imageLinks']['thumbnail'] ?? 
                    $volume['volumeInfo']['imageLinks']['smallThumbnail'] ?? null;
        
        if ($imageUrl) {
            $imagePath = $this->downloadAndSaveImage($imageUrl, $volume['id']);
            if ($imagePath) {
                $data['imagem_capa'] = $imagePath;
            }
        }
        
        $livro = Livro::create($data);
        
        if ($request->has('autores')) {
            $livro->autores()->sync($request->autores);
        }
        
        return redirect()->route('livros.show', $livro->id)
            ->with('success', 'Livro importado da Google Books com sucesso!');
    }
    
    public function apiSearch(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2'
        ]);
        
        $results = $this->googleBooks->search(
            $request->q,
            $request->get('limit', 20)
        );
        
        return response()->json($results);
    }
    
    private function downloadAndSaveImage($url, $volumeId)
    {
        try {
            $contents = Http::get($url)->body();
            $filename = 'google_' . $volumeId . '.jpg';
            $path = 'imagens/livros/' . $filename;
            
            Storage::disk('public')->put($path, $contents);
            
            return $path;
        } catch (\Exception $e) {
            Log::error('Erro ao baixar imagem: ' . $e->getMessage());
            return null;
        }
    }
}