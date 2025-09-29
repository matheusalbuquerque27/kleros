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
        Schema::create('licoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->constrained('modulos')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->text('conteudo')->nullable(); // conteúdo da lição, pode ser Html ou Markdown
            $table->integer('ordem')->default(0); // ordem de exibição da lição dentro do módulo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licoes');
    }
};
