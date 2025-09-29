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
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true); // se o módulo está ativo
            $table->boolean('publico')->default(false); // se o módulo é público ou privado
            $table->string('icone')->nullable(); // ícone do módulo, se necessário
            $table->string('cor')->nullable(); // cor do módulo, se necessário
            $table->string('url')->nullable(); // URL do módulo, se for um link
            $table->unsignedBigInteger('ordem')->default(0); // ordem de exibição do módulo
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modulos');
    }
};
