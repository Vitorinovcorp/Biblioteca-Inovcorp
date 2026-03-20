<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        // Os middlewares são aplicados nas rotas, não aqui
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function toggleAdmin(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Não pode alterar o seu próprio papel de administrador.');
        }

        if ($user->role === 'admin') {
            $user->role = 'cidadão';
            $message = 'Permissões de administrador removidas com sucesso.';
        } else {
            $user->role = 'admin';
            $message = 'Utilizador promovido a administrador com sucesso.';
        }

        $user->save();

        return redirect()->route('users.index')->with('success', $message);
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Não pode eliminar o seu próprio utilizador.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilizador eliminado com sucesso.');
    }
}