<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Autor;
use App\Models\Livro;

class AutorTable extends Component
{
    use WithPagination;

    // Filtros e pesquisa
    public $search = '';
    public $filtroLivro = '';
    public $ordenarPor = 'nome';
    public $ordenarDirecao = 'asc';
    
    // Filtros disponíveis
    public $livros = [];

    // Resetar paginação ao filtrar
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
        // Resetar página ao atualizar qualquer filtro
        if (in_array($property, ['search', 'filtroLivro'])) {
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
        $this->reset(['search', 'filtroLivro', 'ordenarPor', 'ordenarDirecao']);
        $this->ordenarPor = 'nome';
        $this->ordenarDirecao = 'asc';
        $this->resetPage();
    }

    public function render()
    {
        $query = Autor::with('livros')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nome', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filtroLivro, function ($query) {
                $query->whereHas('livros', function ($q) {
                    $q->where('livros.id', $this->filtroLivro);
                });
            })
            ->orderBy($this->ordenarPor, $this->ordenarDirecao);

        $autores = $query->paginate(12);

        return view('livewire.autor-table', [
            'autores' => $autores
        ]);
    }
}