<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Editor;
use App\Models\Livro;

class EditoraTable extends Component
{
    use WithPagination;

    // Filtros e pesquisa
    public $search = '';
    public $filtroLivro = '';
    public $ordenarPor = 'nome';
    public $ordenarDirecao = 'asc';
    
    // Filtros disponíveis
    public $livros = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'filtroLivro' => ['except' => ''],
        'ordenarPor' => ['except' => 'nome'],
        'ordenarDirecao' => ['except' => 'asc'],
    ];

    public function mount()
    {
        $this->livros = Livro::orderBy('nome')->get();
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'filtroLivro', 'ordenarPor', 'ordenarDirecao'])) {
            $this->resetPage();
        }
    }

    public function sortBy($field)
    {
        if ($this->ordenarPor === $field) {
            $this->ordenarDirecao = $this->ordenarDirecao === 'asc' ? 'desc' : 'asc';
        } else {
            $this->ordenarPor = $field;
            $this->ordenarDirecao = 'asc';
        }
        
        $this->resetPage();
    }

    public function limparFiltros()
    {
        $this->reset(['search', 'filtroLivro']);
        $this->ordenarPor = 'nome';
        $this->ordenarDirecao = 'asc';
        $this->resetPage();
    }

    public function render()
    {
        $query = Editor::with('livros')
            ->when($this->search, function ($query) {
                return $query->where('nome', 'like', '%' . $this->search . '%');
            })
            ->when($this->filtroLivro, function ($query) {
                return $query->whereHas('livros', function ($q) {
                    $q->where('livros.id', $this->filtroLivro);
                });
            })
            ->orderBy($this->ordenarPor, $this->ordenarDirecao);

        $editoras = $query->paginate(12);

        return view('livewire.editora-table', [
            'editoras' => $editoras
        ]);
    }
}