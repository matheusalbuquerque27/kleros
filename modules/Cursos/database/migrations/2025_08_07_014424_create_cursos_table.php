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
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('congregacao_id')->nullable()->constrained('congregacoes')->onDelete('set null');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true); // se o curso está ativo
            $table->boolean('publico')->default(false); // se o curso é público ou privado
            $table->string('icone')->nullable(); // ícone do curso, se necessário
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
