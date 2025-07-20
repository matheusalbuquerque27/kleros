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
        Schema::create('grupo_membros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('membro_id');
            $table->unsignedBigInteger('grupo_id');
            $table->foreignId('congregacao_id')->constrained('congregacoes')->onDelete('cascade');
            $table->foreign('membro_id')->references('id')->on('membros')->onDelete('cascade');
            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo_integrantes');
    }
};
