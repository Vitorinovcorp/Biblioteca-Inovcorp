<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Autor;
use App\Models\Editor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Requisicao;

class LivroController extends Controller
{
    public function index()
    {
        $livros = Livro::with('editora', 'autores')->get();
        return view('livros', compact('livros'));
    }

    public function show($id)
    {
        $livro = Livro::with('editora', 'autores')->findOrFail($id);
        $user = Auth::user();
        
        
        if (method_exists($livro, 'requisicoes')) {
            if ($user && $user->role === 'admin') {
                $historico = $livro->requisicoes()
                    ->with('user')
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $historico = $livro->requisicoes()
                    ->where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        } else {
            $historico = collect(); 
        }

        $disponivelAgora = $this->livroDisponivelAgora($id);
        
        return view('livros-show', compact('livro', 'historico', 'disponivelAgora'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('livros.index')
                ->with('error', 'Acesso não autorizado. Apenas administradores.');
        }

        $editoras = Editor::all();
        $autores = Autor::all();
        return view('livros-create', compact('editoras', 'autores'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('livros.index')
                ->with('error', 'Acesso não autorizado. Apenas administradores.');
        }

        $request->validate([
            'isbn' => 'required|unique:livros',
            'nome' => 'required|string|max:255',
            'bibliografia' => 'nullable|string',
            'imagem_capa' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preco' => 'required|numeric|min:0',
            'editora_id' => 'required|exists:editoras,id',
            'autores' => 'nullable|array',
            'autores.*' => 'exists:autores,id',
        ]);

        $data = $request->except('autores');

        if ($request->hasFile('imagem_capa')) {
            $path = $request->file('imagem_capa')->store('imagens/livros', 'public');
            $data['imagem_capa'] = $path;
        }

        $livro = Livro::create($data);

        if ($request->has('autores')) {
            $livro->autores()->sync($request->autores);
        }

        return redirect()->route('livros.index')
            ->with('success', 'Livro criado com sucesso!');
    }

    public function edit($id)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('livros.index')
                ->with('error', 'Acesso não autorizado. Apenas administradores.');
        }

        $livro = Livro::with('autores')->findOrFail($id);
        $editoras = Editor::all();
        $autores = Autor::all();
        
        return view('livros-edit', compact('livro', 'editoras', 'autores'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('livros.index')
                ->with('error', 'Acesso não autorizado. Apenas administradores.');
        }

        $livro = Livro::findOrFail($id);

        $request->validate([
            'isbn' => 'required|unique:livros,isbn,' . $id,
            'nome' => 'required|string|max:255',
            'bibliografia' => 'nullable|string',
            'imagem_capa' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preco' => 'required|numeric|min:0',
            'editora_id' => 'required|exists:editoras,id',
            'autores' => 'nullable|array',
            'autores.*' => 'exists:autores,id',
        ]);

        $data = $request->except('autores');

        if ($request->hasFile('imagem_capa')) {
            
            if ($livro->imagem_capa) {
                Storage::disk('public')->delete($livro->imagem_capa);
            }
            $path = $request->file('imagem_capa')->store('imagens/livros', 'public');
            $data['imagem_capa'] = $path;
        }

        $livro->update($data);

        if ($request->has('autores')) {
            $livro->autores()->sync($request->autores);
        }

        return redirect()->route('livros.index')
            ->with('success', 'Livro atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('livros.index')
                ->with('error', 'Acesso não autorizado. Apenas administradores.');
        }

        $livro = Livro::findOrFail($id);
        
        if ($livro->imagem_capa) {
            Storage::disk('public')->delete($livro->imagem_capa);
        }
        
        $livro->delete();

        return redirect()->route('livros.index')
            ->with('success', 'Livro removido com sucesso!');
    }

    private function livroDisponivelAgora($livroId)
    {
        if (!method_exists(Livro::class, 'requisicoes')) {
            return true; 
        }

        return !Requisicao::where('livro_id', $livroId)
            ->where('status', 'aprovada')
            ->where('data_inicio', '<=', now())
            ->where('data_fim', '>=', now())
            ->exists();
    }
}