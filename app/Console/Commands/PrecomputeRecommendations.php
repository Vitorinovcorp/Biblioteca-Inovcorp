<?php

namespace App\Console\Commands;

use App\Models\Livro;
use App\Services\RecommendationService;
use Illuminate\Console\Command;

class PrecomputeRecommendations extends Command
{
    protected $signature = 'recommendations:precompute';
    protected $description = 'Pré-calcular recomendações para todos os livros';
    
    protected $recommendationService;
    
    public function __construct(RecommendationService $recommendationService)
    {
        parent::__construct();
        $this->recommendationService = $recommendationService;
    }
    
    public function handle()
    {
        $this->info('Iniciando pré-cálculo de recomendações...');
        
        $livros = Livro::all();
        $total = $livros->count();
        
        if ($total === 0) {
            $this->warn('Nenhum livro encontrado no sistema.');
            return 0;
        }
        
        $bar = $this->output->createProgressBar($total);
        $bar->start();
        
        $processed = 0;
        foreach ($livros as $livro) {
            $this->recommendationService->getCachedRelatedBooks($livro, 4);
            $bar->advance();
            $processed++;
        }
        
        $bar->finish();
        $this->newLine(2);
        $this->info("Pré-cálculo concluído com sucesso!");
        $this->info("Processados: {$processed} livros");
        
        return 0;
    }
}