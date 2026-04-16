<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use App\Traits\LogsActivity;

class Encomenda extends Model
{
    //use LogsActivity;
    use HasFactory;

    protected $table = 'encomendas';

    protected $fillable = [
        'user_id',
        'numero_encomenda',
        'total',
        'status_pagamento',
        'stripe_payment_intent_id',
        'stripe_session_id',
        'morada_entrega',
        'codigo_postal',
        'cidade',
        'telefone',
        'pago_em',
    ];

    protected $casts = [
        'pago_em' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function itens()
    {
        return $this->hasMany(EncomendaItem::class);
    }

    public static function gerarNumeroEncomenda()
    {
        return 'INV-' . strtoupper(uniqid());
    }
}