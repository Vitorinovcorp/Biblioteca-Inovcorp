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
}