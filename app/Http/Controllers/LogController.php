<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acesso não autorizado.');
        }

        $query = Log::with('user');

        if ($request->filled('data')) {
            $query->whereDate('data', $request->data);
        }

        if ($request->filled('modulo')) {
            $query->where('modulo', $request->modulo);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('busca')) {
            $query->where('alteracao', 'like', '%' . $request->busca . '%');
        }

        $logs = $query->orderBy('id', 'desc')->paginate(50);
        
        $modulos = Log::distinct()->pluck('modulo');
        $usuarios = User::all();

        return view('logs.index', compact('logs', 'modulos', 'usuarios'));
    }

    public function meusLogs(Request $request)
    {
        $query = Log::where('user_id', Auth::id());

        if ($request->filled('data')) {
            $query->whereDate('data', $request->data);
        }

        if ($request->filled('modulo')) {
            $query->where('modulo', $request->modulo);
        }

        $logs = $query->orderBy('id', 'desc')->paginate(30);
        
        $modulos = Log::where('user_id', Auth::id())->distinct()->pluck('modulo');

        return view('logs.meus-logs', compact('logs', 'modulos'));
    }

    public function show($id)
    {
        $log = Log::with('user')->findOrFail($id);
        
        if (Auth::user()->role !== 'admin' && $log->user_id !== Auth::id()) {
            abort(403, 'Acesso não autorizado.');
        }

        return view('logs.show', compact('log'));
    }

    public function limpar(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acesso não autorizado.');
        }

        $request->validate([
            'data_limite' => 'required|date'
        ]);

        $quantidade = Log::where('data', '<', $request->data_limite)->delete();

        return redirect()->route('logs.index')->with('mensagem', "🗑️ {$quantidade} logs removidos com sucesso!");
    }
}