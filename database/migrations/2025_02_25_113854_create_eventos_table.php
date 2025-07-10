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
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('congregacao_id')->constrained('congregacao')->onDelete('cascade');
            $table->string('nome');
            $table->string('descricao');
            $table->foreignId('membro_id')->nullable()->constrained('membros');
            $table->timestamps();
        });

        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('congregacao_id')->constrained('congregacao')->onDelete('cascade');
            $table->string('titulo');
            $table->foreignId('grupo_id')->nullable()->constrained('grupos');
            $table->text('descricao')->nullable();
            $table->date('data_inicio');
            $table->date('data_encerramento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
        Schema::dropIfExists('grupos');
    }
};
