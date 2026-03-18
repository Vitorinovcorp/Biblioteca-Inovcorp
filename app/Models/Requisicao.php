<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Requisicao extends Model
{
    use HasFactory;

    protected $table = 'requisicoes';

    protected $fillable = [
        'user_id',
        'livro_id',
        'data_inicio',
        'data_fim',
        'status',
        'observacoes',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function livro()
    {
        return $this->belongsTo(Livro::class);
    }

    // Escopo para requisições ativas
    public function scopeAtivas($query)
    {
        return $query->where('status', 'aprovada')
            ->where('data_fim', '>=', now());
    }

    // Verificar se a requisição está ativa no momento
    public function estaAtiva()
    {
        return $this->status === 'aprovada' && $this->data_fim >= now();
    }
    
    // Verificar se o usuário atingiu limite de livros
    public static function usuarioAtingiuLimite($userId)
    {
        $livrosAtivos = self::where('user_id', $userId)
            ->where('status', 'aprovada')
            ->where('data_fim', '>=', now())
            ->count();
            
        return $livrosAtivos >= 3;
    }
}