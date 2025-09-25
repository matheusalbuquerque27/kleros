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
        Schema::create('pesquisas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->foreignId('criada_por')->constrained('membros')->onDelete('cascade');
            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();
            $table->foreignId('congregacao_id')->constrained('congregacoes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pesquisas');
    }
};
