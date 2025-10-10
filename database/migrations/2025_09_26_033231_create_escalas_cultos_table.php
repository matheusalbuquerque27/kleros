<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_escala', function (Blueprint $table) {
            $table->id();
            $table->string('nome'); // Ex: Litúrgica, Recepção, Louvor, Cuidadoras
            $table->foreignId('congregacao_id')->constrained('congregacoes')->cascadeOnDelete();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_escala');
    }
};
