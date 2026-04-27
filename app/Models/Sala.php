<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sala extends Model
{
    use HasFactory;

    protected $table = 'salas';

    protected $fillable = ['nome', 'avatar', 'criado_por'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function utilizadores(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'sala_user')->withTimestamps();
    }

    public function mensagens(): HasMany
    {
        return $this->hasMany(Mensagem::class);
    }

    public function criador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'criado_por');
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                return $this->avatar;
            }
            return asset('storage/' . $this->avatar);
        }
        
        $nome = urlencode($this->nome);
        return "https://ui-avatars.com/api/?name={$nome}&background=7F9CF5&color=fff&size=40&bold=true";
    }

    public function getTotalUtilizadoresAttribute()
    {
        return $this->utilizadores()->count();
    }

    public function hasUser($userId)
    {
        return $this->utilizadores()->where('user_id', $userId)->exists();
    }

    public function addUtilizadores($userIds)
    {
        return $this->utilizadores()->syncWithoutDetaching($userIds);
    }

    public function removeUtilizador($userId)
    {
        return $this->utilizadores()->detach($userId);
    }
}