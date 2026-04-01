<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisicao_id')->constrained('requisicoes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('livro_id')->constrained('livros')->onDelete('cascade');
            $table->text('review');
            $table->integer('rating')->nullable(); 
            $table->enum('status', ['suspenso', 'ativo', 'recusado'])->default('suspenso');
            $table->text('justificativa_recusa')->nullable();
            $table->timestamps();
            
            // Garantir que um usuário só pode fazer uma review por requisição
            $table->unique(['requisicao_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}