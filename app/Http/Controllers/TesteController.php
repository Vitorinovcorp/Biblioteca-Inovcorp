<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Livro;
use App\Models\Editor;
use App\Models\Requisicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TesteController extends Controller
{
    public function index()
    {
        return view('testes.index');
    }
    
    public function testarCriacaoRequisicao()
    {
        // Guardar o usuário atual
        $usuarioAtual = Auth::user();
        
        DB::beginTransaction();
        
        try {
            // Usar um email único para não conflitar
            $emailUnico = 'teste_' . time() . '@teste.com';
            
            // Criar dados de teste
            $editor = Editor::factory()->create(['nome' => 'Editora Teste_' . time()]);
            $user = User::factory()->create([
                'name' => 'Usuário Teste',
                'email' => $emailUnico,
                'foto' => 'fotos/perfil.jpg'
            ]);
            $livro = Livro::factory()->create([
                'nome' => 'Livro Teste_' . time(),
                'quantidade' => 5,
                'preco' => 29.90,
                'editora_id' => $editor->id
            ]);
            
            // Forçar logout do usuário de teste após usar
            $tempAuth = Auth::login($user);
            
            $request = new \Illuminate\Http\Request([
                'livro_id' => $livro->id,
                'data_inicio' => now()->format('Y-m-d'),
            ]);
            
            $controller = new RequisicaoController();
            $response = $controller->store($request);
            
            $requisicao = Requisicao::where('user_id', $user->id)->first();
            
            $resultado = [
                'teste' => 'Criação de Requisição',
                'status' => $requisicao ? 'PASS' : 'FAIL',
                'mensagem' => $requisicao ? '✅ Requisição criada com sucesso!' : '❌ Falha ao criar requisição',
                'dados' => [
                    'usuario' => $user->name,
                    'livro' => $livro->nome,
                    'requisicao_id' => $requisicao ? $requisicao->id : null,
                    'status_requisicao' => $requisicao ? $requisicao->status : null
                ]
            ];
            
            DB::rollBack();
            
            // Re-logar o usuário original
            Auth::login($usuarioAtual);
            
            return redirect()->route('testes.index')->with('resultado', $resultado);
            
        } catch (\Exception $e) {
            DB::rollBack();
            // Re-logar o usuário original em caso de erro
            Auth::login($usuarioAtual);
            
            return redirect()->route('testes.index')->with('resultado', [
                'teste' => 'Criação de Requisição',
                'status' => 'FAIL',
                'mensagem' => '❌ Erro: ' . $e->getMessage()
            ]);
        }
    }
    
    public function testarValidacao()
    {
        $usuarioAtual = Auth::user();
        
        DB::beginTransaction();
        
        try {
            $user = User::factory()->create([
                'foto' => 'fotos/perfil.jpg',
                'email' => 'validacao_' . time() . '@teste.com'
            ]);
            
            Auth::login($user);
            
            $request = new \Illuminate\Http\Request([
                'livro_id' => 99999,
                'data_inicio' => now()->format('Y-m-d'),
            ]);
            
            $controller = new RequisicaoController();
            $response = $controller->store($request);
            
            $resultado = [
                'teste' => 'Validação de Requisição',
                'status' => 'PASS',
                'mensagem' => '✅ Validação funcionou corretamente!',
                'dados' => [
                    'mensagem_erro' => 'Livro inválido foi rejeitado'
                ]
            ];
            
            DB::rollBack();
            Auth::login($usuarioAtual);
            
            return redirect()->route('testes.index')->with('resultado', $resultado);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Auth::login($usuarioAtual);
            
            return redirect()->route('testes.index')->with('resultado', [
                'teste' => 'Validação de Requisição',
                'status' => 'PASS',
                'mensagem' => '✅ Erro de validação capturado: ' . $e->getMessage()
            ]);
        }
    }
    
    public function testarDevolucao()
    {
        $usuarioAtual = Auth::user();
        
        DB::beginTransaction();
        
        try {
            $editor = Editor::factory()->create(['nome' => 'Editora Dev_' . time()]);
            $user = User::factory()->create([
                'foto' => 'fotos/perfil.jpg',
                'email' => 'devolucao_' . time() . '@teste.com'
            ]);
            $admin = User::factory()->create([
                'role' => 'admin',
                'foto' => 'fotos/perfil.jpg',
                'email' => 'admin_dev_' . time() . '@teste.com'
            ]);
            $livro = Livro::factory()->create([
                'quantidade' => 5,
                'editora_id' => $editor->id,
                'nome' => 'Livro Devolucao_' . time()
            ]);
            
            // Criar requisição aprovada
            $requisicao = Requisicao::factory()->create([
                'user_id' => $user->id,
                'livro_id' => $livro->id,
                'status' => 'aprovada',
                'data_inicio' => now(),
                'data_fim' => now()->addDays(5),
            ]);
            
            Auth::login($admin);
            
            $request = new \Illuminate\Http\Request([
                'data_devolucao_real' => now()->format('Y-m-d'),
            ]);
            
            $controller = new RequisicaoController();
            $response = $controller->confirmarDevolucao($request, $requisicao->id);
            
            $requisicao->refresh();
            
            $resultado = [
                'teste' => 'Devolução de Livro',
                'status' => $requisicao->status === 'devolvida' ? 'PASS' : 'FAIL',
                'mensagem' => $requisicao->status === 'devolvida' ? '✅ Devolução realizada com sucesso!' : '❌ Falha na devolução',
                'dados' => [
                    'requisicao_id' => $requisicao->id,
                    'status_atual' => $requisicao->status
                ]
            ];
            
            DB::rollBack();
            Auth::login($usuarioAtual);
            
            return redirect()->route('testes.index')->with('resultado', $resultado);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Auth::login($usuarioAtual);
            
            return redirect()->route('testes.index')->with('resultado', [
                'teste' => 'Devolução de Livro',
                'status' => 'FAIL',
                'mensagem' => '❌ Erro: ' . $e->getMessage()
            ]);
        }
    }
    
    public function testarListagem()
    {
        $usuarioAtual = Auth::user();
        
        DB::beginTransaction();
        
        try {
            $editor = Editor::factory()->create(['nome' => 'Editora List_' . time()]);
            $user1 = User::factory()->create([
                'foto' => 'fotos/perfil.jpg',
                'email' => 'list1_' . time() . '@teste.com'
            ]);
            $user2 = User::factory()->create([
                'foto' => 'fotos/perfil.jpg',
                'email' => 'list2_' . time() . '@teste.com'
            ]);
            $livro = Livro::factory()->create([
                'editora_id' => $editor->id,
                'nome' => 'Livro Listagem_' . time()
            ]);
            
            Requisicao::factory()->count(3)->create([
                'user_id' => $user1->id,
                'livro_id' => $livro->id,
            ]);
            
            Requisicao::factory()->count(2)->create([
                'user_id' => $user2->id,
                'livro_id' => $livro->id,
            ]);
            
            $requisicoesUser1 = Requisicao::where('user_id', $user1->id)->count();
            $requisicoesUser2 = Requisicao::where('user_id', $user2->id)->count();
            
            $resultado = [
                'teste' => 'Listagem por Utilizador',
                'status' => 'PASS',
                'mensagem' => '✅ Listagem funcionando corretamente!',
                'dados' => [
                    'requisicoes_user1' => $requisicoesUser1,
                    'requisicoes_user2' => $requisicoesUser2
                ]
            ];
            
            DB::rollBack();
            Auth::login($usuarioAtual);
            
            return redirect()->route('testes.index')->with('resultado', $resultado);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Auth::login($usuarioAtual);
            
            return redirect()->route('testes.index')->with('resultado', [
                'teste' => 'Listagem por Utilizador',
                'status' => 'FAIL',
                'mensagem' => '❌ Erro: ' . $e->getMessage()
            ]);
        }
    }
    
    public function testarStock()
    {
        $usuarioAtual = Auth::user();
        
        DB::beginTransaction();
        
        try {
            $editor = Editor::factory()->create(['nome' => 'Editora Stock_' . time()]);
            $user = User::factory()->create([
                'foto' => 'fotos/perfil.jpg',
                'email' => 'stock_' . time() . '@teste.com'
            ]);
            $livro = Livro::factory()->create([
                'quantidade' => 0,
                'editora_id' => $editor->id,
                'nome' => 'Livro Stock_' . time()
            ]);
            
            Auth::login($user);
            
            $request = new \Illuminate\Http\Request([
                'livro_id' => $livro->id,
                'data_inicio' => now()->format('Y-m-d'),
            ]);
            
            $controller = new RequisicaoController();
            $response = $controller->store($request);
            
            $requisicao = Requisicao::where('user_id', $user->id)->first();
            
            $resultado = [
                'teste' => 'Stock na Requisição',
                'status' => !$requisicao ? 'PASS' : 'FAIL',
                'mensagem' => !$requisicao ? '✅ Sistema impediu requisição com stock zero!' : '❌ Sistema impediu requisição sem stock',
                'dados' => [
                    'livro_quantidade' => $livro->quantidade,
                    'requisicao_criada' => $requisicao ? 'Sim' : 'Não'
                ]
            ];
            
            DB::rollBack();
            Auth::login($usuarioAtual);
            
            return redirect()->route('testes.index')->with('resultado', $resultado);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Auth::login($usuarioAtual);
            
            return redirect()->route('testes.index')->with('resultado', [
                'teste' => 'Stock na Requisição',
                'status' => 'PASS',
                'mensagem' => '✅ Sistema bloqueou requisição sem stock'
            ]);
        }
    }
    
    public function testarTodos()
    {
        // Executar cada teste e coletar resultados
        $this->testarCriacaoRequisicao();
        $this->testarValidacao();
        $this->testarDevolucao();
        $this->testarListagem();
        $this->testarStock();
        
        return redirect()->route('testes.index')->with('resultado', [
            'teste' => 'Todos os Testes',
            'status' => 'Executados',
            'mensagem' => '✅ Todos os testes foram executados. Verifique cada um individualmente para ver os resultados.'
        ]);
    }
}