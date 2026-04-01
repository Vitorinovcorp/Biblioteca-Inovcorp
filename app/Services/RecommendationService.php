<?php

namespace App\Services;

use App\Models\Livro;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RecommendationService
{

    public function getRelatedBooks(Livro $livro, $limit = 4)
    {
        if (empty($livro->bibliografia)) {
            return $this->getRelatedBooksByMetadata($livro, $limit);
        }
        
        $todosLivros = Livro::with(['autores', 'editora'])
            ->where('id', '!=', $livro->id)
            ->whereNotNull('bibliografia')
            ->where('bibliografia', '!=', '')
            ->get();
        
        if ($todosLivros->isEmpty()) {
            return collect();
        }
        
        $livrosComScore = [];
        
        foreach ($todosLivros as $outroLivro) {
            $score = $this->calculateSimilarityScore($livro, $outroLivro);
            
            if ($score > 0) {
                $livrosComScore[] = [
                    'livro' => $outroLivro,
                    'score' => $score
                ];
            }
        }
        
        usort($livrosComScore, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        $related = array_slice($livrosComScore, 0, $limit);
        
        return collect(array_column($related, 'livro'));
    }
    
    public function calculateSimilarityScore(Livro $livro1, Livro $livro2)
    {
        $score = 0;
        
        $descricaoScore = $this->calculateTextSimilarity(
            $livro1->bibliografia ?? '', 
            $livro2->bibliografia ?? ''
        );
        $score += $descricaoScore * 0.6;
        
        $autoresComuns = $livro1->autores->intersect($livro2->autores)->count();
        $maxAutores = max($livro1->autores->count(), $livro2->autores->count());
        $autorScore = $maxAutores > 0 ? $autoresComuns / $maxAutores : 0;
        $score += $autorScore * 0.25;
        
        $editoraScore = ($livro1->editora_id && $livro2->editora_id && 
                        $livro1->editora_id == $livro2->editora_id) ? 1 : 0;
        $score += $editoraScore * 0.15;
        
        return $score;
    }
    
    private function calculateTextSimilarity($text1, $text2)
    {
        if (empty($text1) || empty($text2)) {
            return 0;
        }
        
        $text1 = $this->normalizeText($text1);
        $text2 = $this->normalizeText($text2);
        
        $stopwords = $this->getPortugueseStopwords();
        
        $words1 = array_diff(str_word_count($text1, 1), $stopwords);
        $words2 = array_diff(str_word_count($text2, 1), $stopwords);
        
        if (empty($words1) || empty($words2)) {
            return 0;
        }
        
        $freq1 = array_count_values($words1);
        $freq2 = array_count_values($words2);
        
        $allWords = array_unique(array_merge(array_keys($freq1), array_keys($freq2)));
        
        $vector1 = [];
        $vector2 = [];
        
        foreach ($allWords as $word) {
            $vector1[] = $freq1[$word] ?? 0;
            $vector2[] = $freq2[$word] ?? 0;
        }
        
        $dotProduct = 0;
        $magnitude1 = 0;
        $magnitude2 = 0;
        
        for ($i = 0; $i < count($vector1); $i++) {
            $dotProduct += $vector1[$i] * $vector2[$i];
            $magnitude1 += pow($vector1[$i], 2);
            $magnitude2 += pow($vector2[$i], 2);
        }
        
        $magnitude1 = sqrt($magnitude1);
        $magnitude2 = sqrt($magnitude2);
        
        if ($magnitude1 == 0 || $magnitude2 == 0) {
            return 0;
        }
        
        return $dotProduct / ($magnitude1 * $magnitude2);
    }
    
    private function getRelatedBooksByMetadata(Livro $livro, $limit = 4)
    {
        $autorIds = $livro->autores->pluck('id');
        
        $query = Livro::with(['autores', 'editora'])
            ->where('id', '!=', $livro->id);
        
        if ($autorIds->isNotEmpty()) {
            $query->whereHas('autores', function($q) use ($autorIds) {
                $q->whereIn('autores.id', $autorIds);
            });
        }
        
        if ($livro->editora_id) {
            $query->orWhere('editora_id', $livro->editora_id);
        }
        
        $related = $query->limit($limit)->get();
        
        if ($related->isEmpty()) {
            $related = Livro::where('id', '!=', $livro->id)
                ->limit($limit)
                ->inRandomOrder()
                ->get();
        }
        
        return $related;
    }
    
    private function normalizeText($text)
    {
        $text = mb_strtolower($text);
        
        $text = preg_replace([
            '/(á|à|ã|â|ä)/', 
            '/(é|è|ê|ë)/', 
            '/(í|ì|î|ï)/', 
            '/(ó|ò|õ|ô|ö)/', 
            '/(ú|ù|û|ü)/', 
            '/ç/'
        ], ['a', 'e', 'i', 'o', 'u', 'c'], $text);
        
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);
        
        return $text;
    }
    
    private function getPortugueseStopwords()
    {
        return [
            'a', 'e', 'o', 'de', 'da', 'do', 'em', 'com', 'para', 'um', 'uma',
            'que', 'se', 'por', 'como', 'mais', 'mas', 'ou', 'no', 'na', 'nos',
            'nas', 'ao', 'aos', 'as', 'os', 'é', 'são', 'está', 'estão', 'foi',
            'foram', 'ser', 'ter', 'tem', 'têm', 'seu', 'sua', 'meu', 'minha',
            'ele', 'ela', 'eles', 'elas', 'nós', 'você', 'vocês', 'este', 'esta',
            'isso', 'aquilo', 'aquele', 'aquela', 'ali', 'aqui', 'lá', 'cá',
            'me', 'te', 'lhe', 'nos', 'vos', 'lhes', 'meu', 'minha', 'seus', 'suas'
        ];
    }
    
    public function getCachedRelatedBooks(Livro $livro, $limit = 4)
    {
        $cacheKey = 'related_books_' . $livro->id;
        
        return cache()->remember($cacheKey, 3600, function() use ($livro, $limit) {
            return $this->getRelatedBooks($livro, $limit);
        });
    }
    
    public function clearCache(Livro $livro)
    {
        $cacheKey = 'related_books_' . $livro->id;
        cache()->forget($cacheKey);
        
        $livrosRecomendados = Livro::all();
        foreach ($livrosRecomendados as $outroLivro) {
            cache()->forget('related_books_' . $outroLivro->id);
        }
    }
}