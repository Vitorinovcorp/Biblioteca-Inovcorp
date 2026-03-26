<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleBooksService
{
    protected $baseUrl = 'https://www.googleapis.com/books/v1/';
    
    public function search($query, $maxResults = 20)
    {
        try {
            $response = Http::get($this->baseUrl . 'volumes', [
                'q' => $query,
                'maxResults' => $maxResults,
            ]);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            Log::warning('Erro na busca do Google Books: ' . $response->status());
            return ['items' => []];
            
        } catch (\Exception $e) {
            Log::error('Erro na busca do Google Books: ' . $e->getMessage());
            return ['items' => []];
        }
    }
    
    public function getVolume($volumeId)
    {
        try {
            $response = Http::get($this->baseUrl . 'volumes/' . $volumeId);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            Log::warning('Erro ao buscar volume: ' . $response->status());
            return null;
            
        } catch (\Exception $e) {
            Log::error('Erro ao buscar volume: ' . $e->getMessage());
            return null;
        }
    }
}