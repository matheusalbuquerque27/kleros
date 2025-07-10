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
        Schema::create('situacao_visitantes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->timestamps();
        });

        Schema::create('visitantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('congregacao_id')->constrained('congregacao')->onDelete('cascade');
            $table->string('nome');
            $table->string('telefone');
            $table->date('data_visita');
            $table->foreignId('sit_visitante_id')->constrained('situacao_visitantes');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitantes');
        Schema::dropIfExists('situacao_visitantes');
    }
};
