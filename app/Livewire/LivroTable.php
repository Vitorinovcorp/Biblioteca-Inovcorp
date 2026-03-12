<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Livro;
use App\Models\Editor;
use App\Models\Autor;

class LivroTable extends Component
{
    use WithPagination;

    // Filtros e pesquisa
    public $search = '';
    public $filtroEditora = '';
    public $filtroAutor = '';
    public $filtroPrecoMin = '';
    public $filtroPrecoMax = '';
    
    // Ordenação
    public $sortField = 'nome';
    public $sortDirection = 'asc';
    
    // Filtros disponíveis
    public $editoras = [];
    public $autores = [];

    // Resetar paginação ao filtrar
    protected $queryString = [
        'search' => ['except' => ''],
        'filtroEditora' => ['except' => ''],
        'filtroAutor' => ['except' => ''],
        'sortField' => ['except' => 'nome'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount()
    {
        $this->editoras = Editor::orderBy('nome')->get();
        $this->autores = Autor::orderBy('nome')->get();
    }

    public function updated($property)
    {
        // Resetar página ao atualizar qualquer filtro
        if (in_array($property, ['search', 'filtroEditora', 'filtroAutor', 'filtroPrecoMin', 'filtroPrecoMax'])) {
            $this->resetPage();
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        
        $this->resetPage();
    }

    public function limparFiltros()
    {
        $this->reset(['search', 'filtroEditora', 'filtroAutor', 'filtroPrecoMin', 'filtroPrecoMax']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Livro::with(['editora', 'autores'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nome', 'like', '%' . $this->search . '%')
                      ->orWhere('isbn', 'like', '%' . $this->search . '%')
                      ->orWhere('bibliografia', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filtroEditora, function ($query) {
                $query->where('editora_id', $this->filtroEditora);
            })
            ->when($this->filtroAutor, function ($query) {
                $query->whereHas('autores', function ($q) {
                    $q->where('autores.id', $this->filtroAutor);
                });
            })
            ->when($this->filtroPrecoMin, function ($query) {
                $query->where('preco', '>=', $this->filtroPrecoMin);
            })
            ->when($this->filtroPrecoMax, function ($query) {
                $query->where('preco', '<=', $this->filtroPrecoMax);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $livros = $query->paginate(12);

        return view('livewire.livro-table', [
            'livros' => $livros
        ]);
    }
}