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
        Schema::create('departamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('congregacao_id')->nullable()->constrained('congregacoes')->onDelete('cascade');
            $table->foreignId('lider_id')->nullable()->constrained('membros')->onDelete('cascade');
            $table->foreignId('colider_id')->nullable()->constrained('membros')->onDelete('cascade');
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
    }
};
