<?php

namespace App\Traits;

use App\Models\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth; 

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            static::registrarLogEstatico($model, 'Criou', $model->getAttributes());
        });

        static::updated(function ($model) {
            $alteracoes = [];
            foreach ($model->getDirty() as $campo => $novoValor) {
                $antigoValor = $model->getOriginal($campo);
                if ($antigoValor != $novoValor) {
                    $antigoStr = is_array($antigoValor) || is_object($antigoValor) ? json_encode($antigoValor) : (string) $antigoValor;
                    $novoStr = is_array($novoValor) || is_object($novoValor) ? json_encode($novoValor) : (string) $novoValor;
                    $alteracoes[] = "{$campo}: '{$antigoStr}' → '{$novoStr}'";
                }
            }
            if (!empty($alteracoes)) {
                static::registrarLogEstatico($model, 'Atualizou', implode('; ', $alteracoes));
            }
        });

        static::deleted(function ($model) {
            $dados = $model->getOriginal();
            $dadosStr = is_array($dados) || is_object($dados) ? json_encode($dados) : (string) $dados;
            static::registrarLogEstatico($model, 'Removeu', $dadosStr);
        });
    }

    protected static function registrarLogEstatico($model, $acao, $detalhes)
    {
        if (is_array($detalhes) || is_object($detalhes)) {
            $detalhes = json_encode($detalhes, JSON_UNESCAPED_UNICODE);
        }
        
        if (strlen($detalhes) > 5000) {
            $detalhes = substr($detalhes, 0, 5000) . '... (truncado)';
        }

        try {
            Log::create([
                'data' => now()->toDateString(),
                'hora' => now()->toTimeString(),
                'user_id' => Auth::check() ? Auth::id() : null,
                'modulo' => class_basename($model),
                'objeto_id' => $model->getKey(), 
                'alteracao' => "{$acao}: " . $detalhes,
                'ip' => Request::ip(),
                'browser' => Request::userAgent(),
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Falha ao salvar log: ' . $e->getMessage());
        }
    }
}