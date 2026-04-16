<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Livro;
use App\Models\Editor;
use App\Models\Requisicao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DevolucaoTest extends TestCase
{
    use RefreshDatabase;

    public function test_utilizador_pode_devolver_um_livro()
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
            'quantidade' => 5,
            'editora_id' => $editor->id
        ]);
        
        $requisicao = Requisicao::factory()->create([
            'user_id' => $user->id,
            'livro_id' => $livro->id,
            'status' => 'aprovada',
            'data_inicio' => now(),
            'data_fim' => now()->addDays(5),
        ]);
        
        $response = $this->actingAs($admin)
            ->post(route('requisicoes.confirmar-devolucao', $requisicao->id), [
                'data_devolucao_real' => now()->format('Y-m-d'),
            ]);
        
        $requisicao->refresh();
        
        $this->assertEquals('devolvida', $requisicao->status);
        
        $response->assertRedirect();
    }

    public function test_apenas_o_dono_da_requisicao_pode_devolver_o_livro()
    {
        $editor = Editor::factory()->create();
        $user = User::factory()->create([
            'foto' => 'fotos/perfil.jpg'
        ]);
        $outroUser = User::factory()->create([
            'foto' => 'fotos/perfil.jpg'
        ]);
        $livro = Livro::factory()->create([
            'quantidade' => 5,
            'editora_id' => $editor->id
        ]);
        
        $requisicao = Requisicao::factory()->create([
            'user_id' => $user->id,
            'livro_id' => $livro->id,
            'status' => 'aprovada',
        ]);
        
        $response = $this->actingAs($outroUser)
            ->post(route('requisicoes.confirmar-devolucao', $requisicao->id), [
                'data_devolucao_real' => now()->format('Y-m-d'),
            ]);
        
        $requisicao->refresh();
        $this->assertEquals('aprovada', $requisicao->status);
    }
}