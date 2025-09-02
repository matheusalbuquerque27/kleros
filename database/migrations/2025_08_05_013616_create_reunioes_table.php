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
        Schema::create('reunioes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('congregacao_id')->constrained('congregacoes')->onDelete('cascade');
            $table->string('assunto');
            $table->text('descricao')->nullable();
            $table->dateTime('data_inicio');
            $table->dateTime('data_fim')->nullable();
            $table->string('local')->nullable();
            $table->enum('tipo', ['lideranca', 'grupo', 'geral', 'outro'])->default('geral');
            $table->boolean('privado')->default(false); // se só alguns membros podem ver
            $table->boolean('online')->default(false); // se é uma reunião online
            $table->string('link_online')->nullable(); // link para reunião online
            $table->timestamps();
        });

        Schema::create('reuniao_membros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reuniao_id');
            $table->unsignedBigInteger('membro_id');
            $table->timestamp('notificado_em')->nullable(); // opcional: quando foi notificado
            $table->boolean('visualizado')->default(false); // opcional: controle de leitura

            $table->timestamps();

            $table->foreign('reuniao_id')->references('id')->on('reunioes')->onDelete('cascade');
            $table->foreign('membro_id')->references('id')->on('membros')->onDelete('cascade');

            $table->unique(['reuniao_id', 'membro_id']); // evitar duplicidade
        });

        Schema::create('reuniao_agrupamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reuniao_id');
            // Campos morphs
            $table->unsignedBigInteger('agrupamento_id');
            $table->timestamps();

            $table->foreign('reuniao_id')->references('id')->on('reunioes')->onDelete('cascade');
            $table->foreign('agrupamento_id')->references('id')->on('agrupamentos')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reuniao_membros');
        Schema::dropIfExists('reuniao_agrupamentos');
        Schema::dropIfExists('reunioes');
    }
};
