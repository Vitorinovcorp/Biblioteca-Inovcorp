<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Livro;
use App\Models\Editor;
use App\Models\Requisicao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListagemRequisicoesTest extends TestCase
{
    use RefreshDatabase;

    public function test_utilizador_consegue_ver_apenas_as_suas_requisicoes()
    {
        $editor = Editor::factory()->create();
        
        $user1 = User::factory()->create([
            'foto' => 'fotos/perfil.jpg'
        ]);
        $user2 = User::factory()->create([
            'foto' => 'fotos/perfil.jpg'
        ]);
        
        $livro = Livro::factory()->create([
            'editora_id' => $editor->id
        ]);
        
        Requisicao::factory()->count(3)->create([
            'user_id' => $user1->id,
            'livro_id' => $livro->id,
        ]);
        
        $requisicoesUser2 = Requisicao::factory()->count(2)->create([
            'user_id' => $user2->id,
            'livro_id' => $livro->id,
        ]);
        
        $response = $this->actingAs($user1)
            ->get(route('requisicoes.index'));
        
        $response->assertStatus(200);
        
        $response->assertDontSeeText($user2->name);
    }

    public function test_admin_consegue_ver_todas_as_requisicoes()
    {
        $editor = Editor::factory()->create();
        
        $admin = User::factory()->create([
            'role' => 'admin',
            'foto' => 'fotos/perfil.jpg'
        ]);
        $user = User::factory()->create([
            'foto' => 'fotos/perfil.jpg'
        ]);
        $livro = Livro::factory()->create([
            'editora_id' => $editor->id
        ]);
        
        Requisicao::factory()->create([
            'user_id' => $user->id,
            'livro_id' => $livro->id,
        ]);
        
        $response = $this->actingAs($admin)
            ->get(route('requisicoes.index'));
        
        $response->assertStatus(200);
        $response->assertSee($user->name);
    }
}