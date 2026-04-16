<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Editor extends Model
{
    use HasFactory;

    protected $table = 'editoras';
    protected $fillable = ['nome', 'logotipo'];
    
    public function livros()
    {
        return $this->hasMany(Livro::class, 'editora_id');
    }
}