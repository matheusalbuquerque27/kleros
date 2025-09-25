<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up(): void {
        Schema::create('respostas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesquisa_id')->constrained('pesquisas')->onDelete('cascade');
            $table->foreignId('pergunta_id')->constrained('perguntas')->onDelete('cascade');
            $table->foreignId('membro_id')->constrained('membros')->onDelete('cascade');
            $table->text('resposta_texto')->nullable();
            $table->timestamps();

            // Evita múltiplas respostas do mesmo membro para a mesma pergunta
            $table->unique(['pesquisa_id', 'pergunta_id', 'membro_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('respostas');
    }
};
