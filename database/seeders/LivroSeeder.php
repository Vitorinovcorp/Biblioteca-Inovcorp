<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Livro;
use App\Models\Editor;
use App\Models\Autor;
use Illuminate\Support\Facades\DB;

class LivroSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar IDs das editoras
        $editoras = [
            'Leya' => Editor::where('nome', 'Leya')->first()->id,
            'Porto Editora' => Editor::where('nome', 'Porto Editora')->first()->id,
            'Assírio & Alvim' => Editor::where('nome', 'Assírio & Alvim')->first()->id,
            'Livros do Brasil' => Editor::where('nome', 'Livros do Brasil')->first()->id,
            'Editora Pergaminho' => Editor::where('nome', 'Editora Pergaminho')->first()->id,
            'Editora Globo' => Editor::where('nome', 'Editora Globo')->first()->id,
            'RTP' => Editor::where('nome', 'RTP')->first()->id,
            'Fontanar' => Editor::where('nome', 'Fontanar')->first()->id,
            'Caminho' => Editor::where('nome', 'Caminho')->first()->id,
        ];
        
        // Mapeamento manual de IDs dos autores (baseado no diagnóstico)
        $autores = [
            'José Saramago' => 8,
            'Luís de Camões' => 6,
            'Fernando Pessoa' => 3,
            'Eça de Queirós' => 2,
            'Yuval Noah Harari' => 10,
            'Charles Darwin' => 1,
            'Almeida Garrett' => 5,
            'Fernão Mendes Pinto' => 4,
            'Robin Sharma' => 7,
            'José Carlos' => 12,
            'Miguel Torga' => 9,
            'Guto Lins' => 11,
        ];

        $livros = [
            [
                'isbn' => '978-989-660-103-5',
                'nome' => 'O Ano da Morte de Ricardo Reis',
                'bibliografia' => 'Obra que acompanha os últimos dias do heterônimo Ricardo Reis em Lisboa, durante o regime de Salazar.',
                'imagem_capa' => 'imagens/livros/ano_da_morte.jpg',
                'preco' => 22.50,
                'editora_id' => $editoras['Porto Editora'],
                'autor_id' => $autores['José Saramago']
            ],
            [
                'isbn' => '978-972-33-9876-5',
                'nome' => 'Ensaio sobre a Cegueira',
                'bibliografia' => 'Distopia que explora o colapso social após uma epidemia de cegueira branca.',
                'imagem_capa' => 'imagens/livros/cegueira.jpg',
                'preco' => 23.90,
                'editora_id' => $editoras['Caminho'],
                'autor_id' => $autores['José Saramago']
            ],
            [
                'isbn' => '978-972-47-5123-6',
                'nome' => 'A Cidade e as Serras',
                'bibliografia' => 'Sátira que contrasta a vida urbana em Paris com a simplicidade do campo português.',
                'imagem_capa' => 'imagens/livros/cidade_e_serras.jpg',
                'preco' => 17.50,
                'editora_id' => $editoras['Livros do Brasil'],
                'autor_id' => $autores['Eça de Queirós']
            ],
            [
                'isbn' => '978-972-85-5413-6',
                'nome' => 'Frei Luís de Sousa',
                'bibliografia' => 'É um drama em três atos de Almeida Garrett, estreado em 1843 e publicado em 1844.',
                'imagem_capa' => 'imagens/livros/Frei_Luis.jpg',
                'preco' => 51.50,
                'editora_id' => $editoras['Porto Editora'],
                'autor_id' => $autores['Almeida Garrett']
            ],
            [
                'isbn' => '978-972-23-4567-8',
                'nome' => 'O Livro do Desassossego',
                'bibliografia' => 'Obra fragmentada composta por reflexões e pensamentos do semi-heterônimo Bernardo Soares.',
                'imagem_capa' => 'imagens/livros/livro_desassossego.jpg',
                'preco' => 26.90,
                'editora_id' => $editoras['Assírio & Alvim'],
                'autor_id' => $autores['Fernando Pessoa']
            ],
            [
                'isbn' => '978-972-21-2985-5',
                'nome' => 'Memorial do Convento',
                'bibliografia' => 'Romance histórico que retrata a construção do Convento de Mafra no século XVIII.',
                'imagem_capa' => 'imagens/livros/memorial_do_convento.jpg',
                'preco' => 24.90,
                'editora_id' => $editoras['Leya'],
                'autor_id' => $autores['José Saramago']
            ],
            [
                'isbn' => '978-972-41-4567-8',
                'nome' => 'O Monge que Vendeu o Seu Ferrari',
                'bibliografia' => 'Fábula inspiradora sobre um advogado que busca sabedoria no Himalaia.',
                'imagem_capa' => 'imagens/livros/O_monge.jpg',
                'preco' => 26.90,
                'editora_id' => $editoras['Editora Pergaminho'],
                'autor_id' => $autores['Robin Sharma']
            ],
            [
                'isbn' => '978-989-876-234-5',
                'nome' => 'A Origem das Espécies',
                'bibliografia' => 'Obra fundamental sobre a evolução das espécies através da seleção natural.',
                'imagem_capa' => 'imagens/livros/origem.jpg',
                'preco' => 39.90,
                'editora_id' => $editoras['Leya'],
                'autor_id' => $autores['Charles Darwin']
            ],
            [
                'isbn' => '978-972-0-04698-3',
                'nome' => 'Os Lusíadas',
                'bibliografia' => 'Epopeia que celebra os feitos dos navegadores portugueses.',
                'imagem_capa' => 'imagens/livros/os_lusiadas.jpg',
                'preco' => 19.90,
                'editora_id' => $editoras['Porto Editora'],
                'autor_id' => $autores['Luís de Camões']
            ],
            [
                'isbn' => '978-972-1-05678-9',
                'nome' => 'Os Maias',
                'bibliografia' => 'Romance que retrata a decadência de uma família aristocrata portuguesa.',
                'imagem_capa' => 'imagens/livros/os_maias.jpg',
                'preco' => 29.90,
                'editora_id' => $editoras['Porto Editora'],
                'autor_id' => $autores['Eça de Queirós']
            ],
            [
                'isbn' => '978-989-665-535-4',
                'nome' => 'Peregrinação',
                'bibliografia' => 'Relato histórico das aventuras de Fernão Mendes Pinto no Oriente.',
                'imagem_capa' => 'imagens/livros/peregrinacao.jpg',
                'preco' => 21.90,
                'editora_id' => $editoras['RTP'],
                'autor_id' => $autores['Fernão Mendes Pinto']
            ],
            [
                'isbn' => '978-972-1-05412-9',
                'nome' => 'O Primo Basílio',
                'bibliografia' => 'Romance sobre as aventuras de Guto Lins no mundo editorial.',
                'imagem_capa' => 'imagens/livros/primo.jpg',
                'preco' => 19.90,
                'editora_id' => $editoras['Editora Globo'],
                'autor_id' => $autores['Guto Lins']
            ],
            [
                'isbn' => '978-989-784-123-4',
                'nome' => 'Sapiens: História Breve da Humanidade',
                'bibliografia' => 'Ensaio que traça a evolução da humanidade.',
                'imagem_capa' => 'imagens/livros/sapiens.jpg',
                'preco' => 34.90,
                'editora_id' => $editoras['Leya'],
                'autor_id' => $autores['Yuval Noah Harari']
            ],
            [
                'isbn' => '978-972-1-10478-6',
                'nome' => 'A Viagem do Elefante',
                'bibliografia' => 'Narrativa inspirada na história real de um elefante que percorreu a Europa.',
                'imagem_capa' => 'imagens/livros/viagem_elefante.jpg',
                'preco' => 29.90,
                'editora_id' => $editoras['Caminho'],
                'autor_id' => $autores['José Saramago']
            ],
        ];

        foreach ($livros as $livro) {
            $autorId = $livro['autor_id'];
            unset($livro['autor_id']);
            
            $novoLivro = Livro::create($livro);
            
            DB::table('autor_livro')->insert([
                'autor_id' => $autorId,
                'livro_id' => $novoLivro->id
            ]);
            
            echo "✅ Livro '{$livro['nome']}' criado com autor ID: {$autorId}\n";
        }
        
        echo "🎉 TODOS OS LIVROS FORAM CRIADOS COM SUCESSO!\n";
    }
}