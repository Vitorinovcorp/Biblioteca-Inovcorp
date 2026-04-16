<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Livro;
use App\Models\Editor;
use App\Models\Requisicao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequisicaoValidacaoTest extends TestCase
{
    use RefreshDatabase;

    public function test_requisicao_nao_pode_ser_criada_sem_livro_valido()
    {
        $user = User::factory()->create([
            'foto' => 'fotos/perfil.jpg'
        ]);
        
        $response = $this->actingAs($user)
            ->post(route('requisicoes.store'), [
                'livro_id' => 99999,
                'data_inicio' => now()->format('Y-m-d'),
            ]);
        
        $this->assertDatabaseCount('requisicoes', 0);
    }

    public function test_requisicao_nao_pode_ser_criada_sem_data_de_inicio()
    {
        $editor = Editor::factory()->create();
        $user = User::factory()->create([
            'foto' => 'fotos/perfil.jpg'
        ]);
        $livro = Livro::factory()->create([
            'editora_id' => $editor->id
        ]);
        
        $response = $this->actingAs($user)
            ->post(route('requisicoes.store'), [
                'livro_id' => $livro->id,
            ]);
        
        $this->assertDatabaseCount('requisicoes', 0);
    }

    public function test_requisicao_nao_pode_ser_criada_com_data_de_fim_anterior_a_data_de_inicio()
    {
        $editor = Editor::factory()->create();
        $user = User::factory()->create([
            'foto' => 'fotos/perfil.jpg'
        ]);
        $livro = Livro::factory()->create([
            'editora_id' => $editor->id
        ]);
        
        $dataInicio = now()->format('Y-m-d');
        
        $this->actingAs($user)
            ->post(route('requisicoes.store'), [
                'livro_id' => $livro->id,
                'data_inicio' => $dataInicio,
            ]);
        
        $this->assertDatabaseCount('requisicoes', 1);
        
        $requisicao = Requisicao::where('user_id', $user->id)->first();
        $this->assertEquals($dataInicio, $requisicao->data_inicio->format('Y-m-d'));
        $this->assertEquals(now()->addDays(5)->format('Y-m-d'), $requisicao->data_fim->format('Y-m-d'));
    }
}