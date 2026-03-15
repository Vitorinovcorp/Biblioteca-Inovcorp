<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create {email}';
    protected $description = 'Promove um usuário existente a administrador';

    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("Usuário com email {$email} não encontrado!");
            $this->info('Usuários disponíveis:');
            $users = User::all(['id', 'name', 'email', 'role']);
            foreach ($users as $user) {
                $this->line("ID: {$user->id} | Nome: {$user->name} | Email: {$user->email} | Role: {$user->role}");
            }
            return 1;
        }

        $user->role = 'admin';
        $user->save();

        $this->info("Usuário {$user->name} foi promovido a administrador com sucesso!");
        return 0;
    }
}