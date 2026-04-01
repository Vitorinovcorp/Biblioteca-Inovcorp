<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'requisicao_id',
        'user_id',
        'livro_id',
        'review',
        'rating',
        'status',
        'justificativa_recusa',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function requisicao()
    {
        return $this->belongsTo(Requisicao::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function livro()
    {
        return $this->belongsTo(Livro::class);
    }

    public function scopeAtivos($query)
    {
        return $query->where('status', 'ativo');
    }

    public function scopeSuspensos($query)
    {
        return $query->where('status', 'suspenso');
    }

    public function isActive()
    {
        return $this->status === 'ativo';
    }

    public function isSuspended()
    {
        return $this->status === 'suspenso';
    }

    public function isRefused()
    {
        return $this->status === 'recusado';
    }
}