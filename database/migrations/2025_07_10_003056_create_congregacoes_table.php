<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PhpParser\Node\Expr\FuncCall;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cidades', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('estado_id')->constrained('estados')->onDelete('cascade');
        });

        Schema::create('estados', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('pais_id')->constrained('paises')->onDelete('cascade');
        });

        Schema::create('paises', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
        });

        Schema::create('congregacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('igreja_id')->constrained('igrejas')->onDelete('cascade');
            $table->string('identificacao');
            $table->boolean('ativa');
            $table->string('endereco')->nullable();
            $table->string('numero')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cep')->nullable();
            $table->foreignId('cidade_id')->constrained('cidades')->onDelete('set null');
            $table->foreignId('estado_id')->constrained('estados')->onDelete('set null');
            $table->foreignId('pais_id')->constrained('paises')->onDelete('set null');
                        
            $table->timestamps();
        });

        Schema::create('temas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('css_caminho');
        });

        Schema::create('congregacao_configuracoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('congregacao_id')->constrained('congregacoes')->onDelete('cascade');
            $table->string('logo_caminho')->nullable();
            $table->string('banner_caminho')->nullable();
            $table->json('conjunto_cores');
            $table->string('font_family')->nullable();
            $table->foreignId('tema_id')->nullable()->constrained('temas')->onDelete('set null');

            $table->timestamps();
            $table->unique('congregacao_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('congregacoes');
        Schema::dropIfExists('congregacoes_configuracoes');
        Schema::dropIfExists('cidades');
        Schema::dropIfExists('estados');
        Schema::dropIfExists('paises');
        Schema::dropIfExists('temas');
    }
};
