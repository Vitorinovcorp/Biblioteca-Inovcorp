<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Carrinho extends Model
{
    use HasFactory;

    protected $table = 'carrinhos';

    protected $fillable = [
        'user_id',
        'session_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function itens()
    {
        return $this->hasMany(CarrinhoItem::class);
    }

    public function getTotalAttribute()
    {
        return $this->itens->sum(function ($item) {
            return $item->preco_unitario * $item->quantidade;
        });
    }

    public function getTotalItensAttribute()
    {
        return $this->itens->sum('quantidade');
    }
}