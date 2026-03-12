<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Editor;

class EditorSeeder extends Seeder
{
    public function run(): void
    {
        $editoras = [
            ['nome' => 'Leya', 'logotipo' => 'imagens/editores/leya.jpg'],
            ['nome' => 'Porto Editora', 'logotipo' => 'imagens/editores/porto.jpg'],
            ['nome' => 'Assírio & Alvim', 'logotipo' => 'imagens/editores/assirio_alvim.jpg'],
            ['nome' => 'Livros do Brasil', 'logotipo' => 'imagens/editores/brasil.jpg'],
            ['nome' => 'Editora Pergaminho', 'logotipo' => 'imagens/editores/pergaminho.jpg'],
            ['nome' => 'Editora Globo', 'logotipo' => 'imagens/editores/globo.jpg'],
            ['nome' => 'RTP', 'logotipo' => 'imagens/editores/rtp.png'],
            ['nome' => 'Fontanar', 'logotipo' => 'imagens/editores/fontanar.png'],
            ['nome' => 'Caminho', 'logotipo' => 'imagens/editores/caminho.png'],
        ];

        foreach ($editoras as $editora) {
            Editor::create($editora);
        }
    }
}