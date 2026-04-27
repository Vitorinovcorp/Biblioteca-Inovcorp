<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class MarkUsersOffline extends Command
{
    protected $signature = 'users:offline';
    protected $description = 'Marca usuários inativos como offline';

    public function handle()
    {
        $users = User::where('estado', 'online')->get();
        
        foreach ($users as $user) {
            $cacheKey = 'user-is-online-' . $user->id;
            if (!Cache::has($cacheKey)) {
                $user->update(['estado' => 'offline']);
                $this->info("Usuário {$user->name} marcado como offline");
            }
        }
        
        $this->info('Verificação concluída!');
    }
}