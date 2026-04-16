<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->date('data');
            $table->time('hora');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('modulo', 100);
            $table->unsignedBigInteger('objeto_id')->nullable();
            $table->text('alteracao');
            $table->string('ip', 45)->nullable();
            $table->string('browser', 500)->nullable();
            $table->timestamps();
            
            $table->index(['data', 'modulo']);
            $table->index('user_id');
            $table->index('objeto_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('logs');
    }
};