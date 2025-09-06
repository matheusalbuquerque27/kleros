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
            $table->string('sigla')->nullable();
            $table->text('descricao')->nullable();
            $table->foreignId('denominacao_id')->constrained('denominacoes')->onDelete('cascade');
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
            $table->foreignId('congregacao_id')->constrained('congregacoes')->onDelete('cascade');
            $table->string('nome');
            $table->string('rg')->nullable();
            $table->string('cpf')->nullable();
            $table->date('data_nascimento');
            $table->enum('sexo', ['Masculino', 'Feminino'])->nullable();
            $table->string('telefone');
            $table->string('email')->nullable();
            $table->foreignId('estado_civ_id')->nullable()->constrained('estado_civs'); // Cria a chave estrangeira
            $table->foreignId('escolaridade_id')->nullable()->constrained('escolaridades'); // Cria a chave estrangeira
            $table->string('profissao')->nullable();
            $table->string('endereco')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cep')->nullable();
            $table->date('data_batismo')->nullable();
            $table->string('denominacao_origem')->nullable();
            $table->foreignId('ministerio_id')->nullable()->constrained('ministerios')->onDelete('set null'); // Cria a chave estrangeira
            $table->date('data_consagracao')->nullable();
            $table->string('nome_paterno')->nullable();
            $table->string('nome_materno')->nullable();
            $table->string('foto')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Cria a chave estrangeira
            $table->boolean('ativo')->default(true);
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
