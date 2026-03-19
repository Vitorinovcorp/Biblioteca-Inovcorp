<?php
// database/migrations/xxxx_add_devolucao_fields_to_requisicoes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requisicoes', function (Blueprint $table) {
            $table->date('data_devolucao_real')->nullable()->after('data_fim');
            $table->integer('dias_atraso')->default(0)->after('data_devolucao_real');
            $table->text('observacoes_devolucao')->nullable()->after('dias_atraso');
        });
    }

    public function down(): void
    {
        Schema::table('requisicoes', function (Blueprint $table) {
            $table->dropColumn(['data_devolucao_real', 'dias_atraso', 'observacoes_devolucao']);
        });
    }
};