<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabela de carrinhos
        Schema::create('carrinhos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('session_id')->unique()->nullable();
            $table->string('status')->default('aberto'); 
            $table->timestamps();
        });

        // Tabela de itens do carrinho
        Schema::create('carrinho_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carrinho_id')->constrained()->onDelete('cascade');
            $table->foreignId('livro_id')->constrained()->onDelete('cascade');
            $table->integer('quantidade')->default(1);
            $table->decimal('preco_unitario', 10, 2);
            $table->timestamps();
        });

        // Tabela de encomendas
        Schema::create('encomendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('numero_encomenda')->unique();
            $table->decimal('total', 10, 2);
            $table->string('status_pagamento')->default('pendente'); 
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_session_id')->nullable();
            $table->text('morada_entrega');
            $table->string('codigo_postal');
            $table->string('cidade');
            $table->string('telefone')->nullable();
            $table->timestamp('pago_em')->nullable();
            $table->timestamps();
        });

        // Tabela de itens da encomenda
        Schema::create('encomenda_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encomenda_id')->constrained()->onDelete('cascade');
            $table->foreignId('livro_id')->constrained()->onDelete('cascade');
            $table->integer('quantidade');
            $table->decimal('preco_unitario', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('encomenda_itens');
        Schema::dropIfExists('encomendas');
        Schema::dropIfExists('carrinho_itens');
        Schema::dropIfExists('carrinhos');
    }
};