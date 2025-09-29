<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('respostas_usuarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('membros')->onDelete('cascade');
            $table->foreignId('exercicio_id')->constrained('exercicios')->onDelete('cascade');
            $table->text('resposta'); // Resposta do usuário, pode ser texto livre ou JSON dependendo do tipo de exercício
            $table->boolean('correta')->default(false); // Indica se a resposta está correta
            $table->integer('pontuacao')->default(0); // Pontuação obtida na resposta
            $table->timestamp('respondido_em')->nullable(); // Quando o usuário respondeu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respostas_usuarios');
    }
};
