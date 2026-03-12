<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Autor;
use App\Models\Editor;
use App\Models\Livro;
use Illuminate\Support\Facades\DB;

class DiagnosticoSeeder extends Seeder
{
    public function run(): void
    {
        echo "=== DIAGNÓSTICO ===\n";
        
        // Autores
        $autores = Autor::all();
        echo "Autores encontrados: " . $autores->count() . "\n";
        foreach($autores as $a) {
            echo "  - ID: {$a->id} | Nome: {$a->nome}\n";
        }
        
        // Editoras
        $editoras = Editor::all();
        echo "\nEditoras encontradas: " . $editoras->count() . "\n";
        foreach($editoras as $e) {
            echo "  - ID: {$e->id} | Nome: {$e->nome}\n";
        }
        
        // Livros
        $livros = Livro::with('autores')->get();
        echo "\nLivros encontrados: " . $livros->count() . "\n";
        foreach($livros as $l) {
            echo "  - ID: {$l->id} | Nome: {$l->nome}\n";
        }
    }
}