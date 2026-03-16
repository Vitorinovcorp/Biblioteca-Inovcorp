<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Autor;
use App\Models\Editor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        return view('livros-show', compact('livro'));
    }

    // Métodos que requerem admin (verificação manual)
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('livros.index')
                ->with('error', 'Acesso não autorizado. Apenas administradores.');
        }

        $editoras = Editor::all();
        $autores = Autor::all();
        return view('livros-create', compact('editoras', 'autores'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
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
        if (Auth::user()->role !== 'admin') {
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
        if (Auth::user()->role !== 'admin') {
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
        if (Auth::user()->role !== 'admin') {
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
}