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
        Schema::create('ministerios', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->timestamps();
        });

        Schema::create('estado_civs', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->timestamps();
        });

        Schema::create('escolaridades', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->timestamps();
        });

        Schema::create('membros', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('rg')->nullable();
            $table->string('cpf')->nullable();
            $table->date('data_nascimento');
            $table->string('telefone');
            $table->foreignId('estado_civ_id')->nullable()->constrained('estado_civs'); // Cria a chave estrangeira
            $table->foreignId('escolaridade_id')->nullable()->constrained('escolaridades'); // Cria a chave estrangeira
            $table->string('profissao')->nullable();
            $table->string('endereco')->nullable();
            $table->string('numero')->nullable();
            $table->string('bairro')->nullable();
            $table->date('data_batismo')->nullable();
            $table->string('denominacao_origem')->nullable();
            $table->foreignId('ministerio_id')->nullable()->constrained('ministerios'); // Cria a chave estrangeira
            $table->date('data_consagracao')->nullable();
            $table->string('nome_paterno')->nullable();
            $table->string('nome_materno')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membros');
        Schema::dropIfExists('ministerios');
        Schema::dropIfExists('estado_civs');
        Schema::dropIfExists('escolaridades');
    }
};
