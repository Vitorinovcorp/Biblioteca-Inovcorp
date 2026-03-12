<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autor_livro', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('autor_id');
            $table->unsignedBigInteger('livro_id');
            $table->timestamps(); // Adiciona created_at e updated_at

            $table->foreign('autor_id')
                  ->references('id')
                  ->on('autores')
                  ->onDelete('cascade');

            $table->foreign('livro_id')
                  ->references('id')
                  ->on('livros')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autor_livro');
    }
};