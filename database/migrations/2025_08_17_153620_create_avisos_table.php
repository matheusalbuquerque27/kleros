<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('avisos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('mensagem');
            $table->boolean('para_todos')->default(false); // toda a igreja
            $table->json('destinatarios_agrupamentos')->nullable(); // IDs de grupos
            $table->timestamp('data_inicio')->nullable(); // início da exibição
            $table->timestamp('data_fim')->nullable();     // fim da exibição
            $table->enum('status', ['ativo', 'arquivado'])->default('ativo');
            $table->unsignedBigInteger('criado_por'); // pastor/líder que criou
            $table->timestamps();

            $table->foreign('criado_por')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('avisos');
    }
};