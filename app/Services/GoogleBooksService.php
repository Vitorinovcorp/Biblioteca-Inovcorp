<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleBooksService
{
    protected $baseUrl = 'https://www.googleapis.com/books/v1';
    protected $apiKey; 
    
    public function __construct()
    {
        $this->apiKey = env('GOOGLE_BOOKS_API_KEY', null);
    }
    
    public function search(string $query, int $maxResults = 20, int $startIndex = 0): array
    {
        $params = [
            'q' => $query,
            'maxResults' => $maxResults,
            'startIndex' => $startIndex,
        ];
        
        if ($this->apiKey) {
            $params['key'] = $this->apiKey;
        }
        
        $response = Http::get("{$this->baseUrl}/volumes", $params);
        
        if ($response->failed()) {
            Log::error('Google Books API error: ' . $response->body());
            return ['items' => [], 'totalItems' => 0, 'error' => $response->json()];
        }
        
        return $response->json();
    }
    
    public function getVolume(string $volumeId): ?array
    {
        $params = [];
        if ($this->apiKey) {
            $params['key'] = $this->apiKey;
        }
        
        $response = Http::get("{$this->baseUrl}/volumes/{$volumeId}", $params);
        
        if ($response->failed()) {
            return null;
        }
        
        return $response->json();
    }
    
    public function mapToLivroData(array $googleBook, ?int $editoraId = null): array
    {
        $volumeInfo = $googleBook['volumeInfo'] ?? [];
        
        $isbn = $this->extractIsbn($volumeInfo['industryIdentifiers'] ?? []);
        
        $autoresNomes = $volumeInfo['authors'] ?? [];
        
        $thumbnail = $volumeInfo['imageLinks']['thumbnail'] ?? 
                     $volumeInfo['imageLinks']['smallThumbnail'] ?? null;
        
        return [
            'external_id' => $googleBook['id'] ?? null,
            'isbn' => $isbn,
            'nome' => $volumeInfo['title'] ?? 'Sem título',
            'bibliografia' => $volumeInfo['description'] ?? null,
            'imagem_capa_url' => $thumbnail,
            'preco' => null, 
            'editora_id' => $editoraId,
            'autores_nomes' => $autoresNomes,
            'published_date' => $volumeInfo['publishedDate'] ?? null,
            'page_count' => $volumeInfo['pageCount'] ?? null,
            'categories' => $volumeInfo['categories'] ?? [],
        ];
    }
    
    private function extractIsbn(array $identifiers): ?string
    {
        foreach ($identifiers as $id) {
            if ($id['type'] === 'ISBN_13') {
                return $id['identifier'];
            }
        }
        foreach ($identifiers as $id) {
            if ($id['type'] === 'ISBN_10') {
                return $id['identifier'];
            }
        }
        return null;
    }
}