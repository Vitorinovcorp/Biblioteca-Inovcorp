<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mensagem extends Model
{
    use HasFactory;

    protected $table = 'mensagens';

    protected $fillable = ['sala_id', 'user_id', 'conteudo', 'tipo', 'anexo'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function sala(): BelongsTo
    {
        return $this->belongsTo(Sala::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}