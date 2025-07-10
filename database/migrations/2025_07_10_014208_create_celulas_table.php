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
        Schema::create('celulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('congregacao_id')->constrained('congregacoes')->onDelete('cascade');
            $table->string('identificacao');
            $table->foreignId('lider_id')->nullable()->constrained('membros')->onDelete('set null');
            $table->foreignId('anfitriao_id')->nullable()->constrained('membros')->onDelete('set null');
            $table->string('endereco')->nullable();
            $table->string('numero')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cep')->nullable();
            $table->tinyInteger('dia_encontro')->nullable()->comment('Dia da semana da reunião (1=Segunda, 7=Domingo)');
            $table->time('hora_encontro')->nullable()->comment('Hora da reunião (HH:MM:SS)');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('celulas');
    }
};
