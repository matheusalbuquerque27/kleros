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
        Schema::create('resposta_opcao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resposta_id')->constrained('respostas')->onDelete('cascade');
            $table->foreignId('opcao_id')->constrained('opcoes_pergunta')->onDelete('cascade');
            $table->timestamps();

            // Evita duplicar a mesma opção na mesma resposta
            $table->unique(['resposta_id', 'opcao_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resposta_opcao');
    }
};
