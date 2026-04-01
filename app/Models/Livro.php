<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Livro extends Model
{
    use HasFactory;

    protected $table = 'livros';
    protected $fillable = [
        'isbn',
        'nome',
        'bibliografia',
        'imagem_capa',
        'preco',
        'editora_id',
        'external_id',
        'quantidade',  
        'user_id', 
    ];
    
    protected $casts = [
        'bibliografia' => 'encrypted',
    ];

    public function editora()
    {
        return $this->belongsTo(Editor::class);
    }

    public function autores()
    {
        return $this->belongsToMany(Autor::class, 'autor_livro', 'livro_id', 'autor_id');
    }

    public function requisicoes()
    {
        return $this->hasMany(Requisicao::class);
    }

    public function requisicoesAtivas()
    {
        return $this->requisicoes()
            ->where('status', 'aprovada')
            ->where('data_fim', '>=', now());
    }

    public function estaDisponivelPara($dataInicio, $dataFim)
    {
        return !$this->requisicoes()
            ->where('status', 'aprovada')
            ->where(function($query) use ($dataInicio, $dataFim) {
                $query->whereBetween('data_inicio', [$dataInicio, $dataFim])
                    ->orWhereBetween('data_fim', [$dataInicio, $dataFim])
                    ->orWhere(function($q) use ($dataInicio, $dataFim) {
                        $q->where('data_inicio', '<=', $dataInicio)
                          ->where('data_fim', '>=', $dataFim);
                    });
            })
            ->exists();
    }

    public function estaDisponivelAgora()
    {
        return $this->estaDisponivelPara(now(), now()->addDay());
    }

    public function historicoRequisicoes()
    {
        return $this->requisicoes()
            ->with('user')
            ->orderBy('created_at', 'desc');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function reviewsAtivas()
    {
        return $this->reviews()->where('status', 'ativo');
    }
}