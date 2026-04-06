<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LivroNotification extends Model
{
    use HasFactory;

    protected $table = 'livro_notifications';

    protected $fillable = [
        'user_id',
        'livro_id',
        'email',
        'notificado',
        'notified_at',
    ];

    protected $casts = [
        'notificado' => 'boolean',
        'notified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function livro()
    {
        return $this->belongsTo(Livro::class);
    }

    public function scopeNaoNotificados($query)
    {
        return $query->where('notificado', false);
    }
}