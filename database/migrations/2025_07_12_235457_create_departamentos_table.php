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
        Schema::create('setores', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('congregacao_id')->constrained('congregacoes')->onDelete('cascade');
        });

        Schema::create('departamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('congregacao_id')->constrained('congregacoes')->onDelete('cascade');
            $table->foreignId('setor_id')->nullable()->constrained('setores')->onDelete('set null');
            $table->foreignId('lider_id')->nullable()->constrained('membros')->onDelete('set null');
            $table->foreignId('colider_id')->nullable()->constrained('membros')->onDelete('set null');
            $table->string('nome');
            $table->string('descricao')->nullable();
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departamentos');
        Schema::dropIfExists('setores');
    }
};
