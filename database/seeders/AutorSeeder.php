<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Autor;

class AutorSeeder extends Seeder
{
    public function run(): void
    {
        $autores = [
            ['nome' => 'Charles Darwin', 'foto' => 'imagens/autores/Darwin.jpg'],
            ['nome' => 'Eca de Queirós', 'foto' => 'imagens/autores/Eca_de_Queiroz.png'],
            ['nome' => 'Fernando Pessoa', 'foto' => 'imagens/autores/Fernando.jpg'],
            ['nome' => 'Fernão Mendes', 'foto' => 'imagens/autores/Fernao_Mendes.jpg'],
            ['nome' => 'Almeida Garrett', 'foto' => 'imagens/autores/Almeida.jpg'],
            ['nome' => 'Luis de Camões', 'foto' => 'imagens/autores/Luis_de_Camoes.png'],
            ['nome' => 'Robin Sharma', 'foto' => 'imagens/autores/Robin_Sharma.jpg'],
            ['nome' => 'José Saramago', 'foto' => 'imagens/autores/Saramago.jpg'],
            ['nome' => 'Miguel Torga', 'foto' => 'imagens/autores/Torga.jpg'],
            ['nome' => 'Yuval Noah Harari', 'foto' => 'imagens/autores/Yuval_Noah.jpg'],
            ['nome' => 'Guto Lins', 'foto' => 'imagens/autores/Guto.jpg'],
            ['nome' => 'Jose Carlos', 'foto' => 'imagens/autores/Jose_Carlos.jpg'],
        ];

        foreach ($autores as $autor) {
            Autor::create($autor);
        }
    }
}