<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Livro;
use App\Models\Editor;
use App\Models\Requisicao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockRequisicaoTest extends TestCase
{
    use RefreshDatabase;

    public function test_nao_e_possivel_requisitar_livro_sem_stock_disponivel()
    {
        $editor = Editor::factory()->create();  
        $user = User::factory()->create([
            'foto' => 'fotos/perfil.jpg'
        ]);
        $livro = Livro::factory()->create([
            'quantidade' => 0,
            'editora_id' => $editor->id  
        ]);
        
        $response = $this->actingAs($user)
            ->post(route('requisicoes.store'), [
                'livro_id' => $livro->id,
                'data_inicio' => now()->format('Y-m-d'),
            ]);
        
        $this->assertDatabaseMissing('requisicoes', [
            'user_id' => $user->id,
            'livro_id' => $livro->id,
        ]);
        
        $response->assertSessionHas('error');
    }

    public function test_apos_requisicao_aprovada_stock_diminui()
    {
        $editor = Editor::factory()->create();  
        $user = User::factory()->create([
            'foto' => 'fotos/perfil.jpg'
        ]);
        $admin = User::factory()->create([
            'role' => 'admin',
            'foto' => 'fotos/perfil.jpg'
        ]);
        $quantidadeInicial = 10;
        $livro = Livro::factory()->create([
            'quantidade' => $quantidadeInicial,
            'editora_id' => $editor->id  
        ]);
        
        $this->actingAs($user)
            ->post(route('requisicoes.store'), [
                'livro_id' => $livro->id,
                'data_inicio' => now()->format('Y-m-d'),
            ]);
        
        $requisicao = Requisicao::where('user_id', $user->id)->first();
        $this->assertNotNull($requisicao, 'Requisição não foi criada');
        
        $this->actingAs($admin)
            ->post(route('requisicoes.status', $requisicao->id), [
                'status' => 'aprovada'
            ]);
        
        $requisicao->refresh();
        $this->assertEquals('aprovada', $requisicao->status);
        
        $livro->refresh();
        $this->assertEquals($quantidadeInicial - 1, $livro->quantidade);
    }

    public function test_quando_stock_chega_a_zero_livro_fica_indisponivel()
    {
        $editor = Editor::factory()->create();  
        $user = User::factory()->create([
            'foto' => 'fotos/perfil.jpg'
        ]);
        $admin = User::factory()->create([
            'role' => 'admin',
            'foto' => 'fotos/perfil.jpg'
        ]);
        $livro = Livro::factory()->create([
            'quantidade' => 1,
            'editora_id' => $editor->id  
        ]);
        
        $this->actingAs($user)
            ->post(route('requisicoes.store'), [
                'livro_id' => $livro->id,
                'data_inicio' => now()->format('Y-m-d'),
            ]);
        
        $requisicao = Requisicao::where('user_id', $user->id)->first();
        $this->assertNotNull($requisicao, 'Requisição não foi criada');
        
        $this->actingAs($admin)
            ->post(route('requisicoes.status', $requisicao->id), [
                'status' => 'aprovada'
            ]);
        
        $requisicao->refresh();
        $this->assertEquals('aprovada', $requisicao->status);
        
        $livro->refresh();
        $this->assertEquals(0, $livro->quantidade);
        
        $user2 = User::factory()->create([
            'foto' => 'fotos/perfil.jpg'
        ]);
        
        $response = $this->actingAs($user2)
            ->post(route('requisicoes.store'), [
                'livro_id' => $livro->id,
                'data_inicio' => now()->format('Y-m-d'),
            ]);
        
        $this->assertDatabaseMissing('requisicoes', [
            'user_id' => $user2->id,
            'livro_id' => $livro->id,
        ]);
        
        $this->actingAs($admin)
            ->post(route('requisicoes.confirmar-devolucao', $requisicao->id), [
                'data_devolucao_real' => now()->format('Y-m-d'),
            ]);
        
        $requisicao->refresh();
        $this->assertEquals('devolvida', $requisicao->status);
        
        $livro->refresh();
        $this->assertEquals(1, $livro->quantidade);
    }
}