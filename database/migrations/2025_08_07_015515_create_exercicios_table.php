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
        Schema::create('exercicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licao_id')->constrained('licoes')->onDelete('cascade');
            $table->text('pergunta');
            $table->enum('tipo', ['multipla_escolha', 'verdadeiro_falso', 'resposta_aberta']);
            $table->json('alternativas')->nullable(); // Usado em múltipla escolha
            $table->string('resposta_correta')->nullable(); // Ex: 'a', 'b', 'c' ou texto livre
            $table->integer('pontuacao')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercicios');
    }
};
