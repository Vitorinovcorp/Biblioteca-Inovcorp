<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarrinhoItem extends Model
{
    use HasFactory;

    protected $table = 'carrinho_itens';

    protected $fillable = [
        'carrinho_id',
        'livro_id',
        'quantidade',
        'preco_unitario',
    ];

    public function carrinho()
    {
        return $this->belongsTo(Carrinho::class);
    }

    public function livro()
    {
        return $this->belongsTo(Livro::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->quantidade * $this->preco_unitario;
    }
}