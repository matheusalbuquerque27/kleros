<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItensEscalaTable extends Migration
{
    public function up()
    {
        Schema::create('itens_escala', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escala_id')->constrained('escalas')->cascadeOnDelete();
            
            $table->string('funcao'); 
            // Ex: "Abertura", "Louvor", "Recepção porta 1", "Teclado", "Cuidadora Infantil"

            $table->foreignId('membro_id')->nullable()->constrained('membros')->nullOnDelete();
            $table->string('responsavel_externo')->nullable(); 
            // quando não for membro cadastrado

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('itens_escala');
    }
}