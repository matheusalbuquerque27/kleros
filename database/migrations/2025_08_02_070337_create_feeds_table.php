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
        Schema::create('feeds', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('titulo');
            $table->string('link', 512)->nullable(); // link da notícia (se externo)
            $table->string('slug')->unique();        // slug para URL amigável
            $table->text('descricao')->nullable();   // resumo da notícia
            $table->longText('conteudo')->nullable(); // corpo completo (com HTML)
            $table->string('imagem_capa', 512)->nullable(); // imagem principal (path ou URL)
            $table->string('fonte')->nullable();     // fonte ou portal
            $table->enum('tipo', ['manual', 'rss', 'outro'])->default('manual'); // origem
            $table->enum('categoria', ['noticia', 'podcast'])->default('noticia');
            $table->string('media_url')->nullable();
            $table->timestamp('publicado_em')->nullable(); // data da publicação original

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed');
    }
};
