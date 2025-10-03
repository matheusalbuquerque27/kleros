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
        Schema::create('projetos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('congregacao_id')->constrained('congregacoes')->onDelete('cascade');
            $table->string('nome');
            $table->string('cor', 20)->nullable();
            $table->boolean('para_todos')->default(false);
            $table->timestamps();
        });

        Schema::create('projetos_listas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projeto_id')->constrained('projetos')->onDelete('cascade');
            $table->string('titulo');
            $table->timestamps();
        });

        Schema::create('cards_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projeto_id')->constrained('projetos')->onDelete('cascade');
            $table->string('nome');
            $table->unsignedInteger('ordem')->default(0);
            $table->timestamps();
        });

        Schema::create('projetos_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lista_id')->constrained('projetos_listas')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->foreignId('status_id')->nullable()->constrained('cards_status')->nullOnDelete();
            $table->date('data_inicio')->nullable();
            $table->date('data_entrega')->nullable();
            $table->string('anexo')->nullable();
            $table->timestamps();
        });

        Schema::create('projetos_membros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projeto_id')->constrained('projetos')->onDelete('cascade');
            $table->foreignId('membro_id')->constrained('membros')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('projetos_agrupamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projeto_id')->constrained('projetos')->onDelete('cascade');
            $table->foreignId('agrupamento_id')->constrained('agrupamentos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projetos_agrupamentos');
        Schema::dropIfExists('projetos_membros');
        Schema::dropIfExists('projetos_cards');
        Schema::dropIfExists('cards_status');
        Schema::dropIfExists('projetos_listas');
        Schema::dropIfExists('projetos');
    }
};
