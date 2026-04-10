<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EncomendaItem extends Model
{
    use HasFactory;

    protected $table = 'encomenda_itens';

    protected $fillable = [
        'encomenda_id',
        'livro_id',
        'quantidade',
        'preco_unitario',
    ];

    public function encomenda()
    {
        return $this->belongsTo(Encomenda::class);
    }

    public function livro()
    {
        return $this->belongsTo(Livro::class);
    }
}