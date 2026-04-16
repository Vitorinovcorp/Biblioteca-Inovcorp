<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Livro;
use App\Models\Editor;
use App\Models\Requisicao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequisicaoTest extends TestCase
{
    use RefreshDatabase;

    public function test_utilizador_pode_criar_uma_requisicao_de_livro_corretamente()
    {
        $editor = Editor::factory()->create();
        $user = User::factory()->create([
            'foto' => 'fotos/perfil.jpg'
        ]);
        $livro = Livro::factory()->create([
            'quantidade' => 5,
            'preco' => 29.90,
            'editora_id' => $editor->id
        ]);
        
        $response = $this->actingAs($user)
            ->post(route('requisicoes.store'), [
                'livro_id' => $livro->id,
                'data_inicio' => now()->format('Y-m-d'),
            ]);
        
        $this->assertDatabaseHas('requisicoes', [
            'user_id' => $user->id,
            'livro_id' => $livro->id,
            'status' => 'pendente',
        ]);
        
        $response->assertRedirect();
    }

    public function test_requisicao_criada_tem_dados_corretos()
    {
        $editor = Editor::factory()->create();
        $user = User::factory()->create([
            'foto' => 'fotos/perfil.jpg'
        ]);
        $livro = Livro::factory()->create([
            'quantidade' => 5,
            'editora_id' => $editor->id
        ]);
        
        $dataInicio = now()->format('Y-m-d');
        $dataFimEsperada = now()->addDays(5)->format('Y-m-d');
        
        $response = $this->actingAs($user)
            ->post(route('requisicoes.store'), [
                'livro_id' => $livro->id,
                'data_inicio' => $dataInicio,
            ]);
        
        if (session('error')) {
            $this->fail('Erro: ' . session('error'));
        }
        
        $requisicao = Requisicao::where('user_id', $user->id)->first();
        
        $this->assertNotNull($requisicao, 'Requisição não foi criada');
        $this->assertEquals($livro->id, $requisicao->livro_id);
        $this->assertEquals($dataInicio, $requisicao->data_inicio->format('Y-m-d'));
        $this->assertEquals($dataFimEsperada, $requisicao->data_fim->format('Y-m-d'));
        $this->assertEquals('pendente', $requisicao->status);
    }
}