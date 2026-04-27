<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'foto',
        'telefone',
        'estado',      
        'avatar',      
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCidadao(): bool
    {
        return $this->role === 'cidadão';
    }


    public function salas()
    {
        return $this->belongsToMany(Sala::class, 'sala_user');
    }

    public function mensagens()
    {
        return $this->hasMany(Mensagem::class);
    }

    public function salasCriadas()
    {
        return $this->hasMany(Sala::class, 'criado_por');
    }

    public function getAvatarUrlAttribute()
{
    if ($this->avatar && !empty($this->avatar)) {
        if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }
        return asset('storage/' . $this->avatar);
    }
    
    if ($this->foto && !empty($this->foto)) {
        return asset('storage/' . $this->foto);
    }
    
    $name = urlencode($this->name);
    return "https://ui-avatars.com/api/?name={$name}&background=7F9CF5&color=fff&size=32&bold=true";
}

    public function atualizarStatus($status)
    {
        $this->estado = $status;
        $this->save();
    }
}