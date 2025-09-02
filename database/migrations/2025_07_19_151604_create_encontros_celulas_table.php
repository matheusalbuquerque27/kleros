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
        Schema::create('encontros_celulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('celula_id')->constrained('celulas')->onDelete('cascade');
            $table->foreignId('congregacao_id')->constrained('congregacoes')->onDelete('cascade');
            $table->enum('status', ['pendente', 'confirmado', 'cancelado'])->default('pendente');
            $table->date('data_encontro');
            $table->time('hora_encontro');
            $table->foreignId('preletor_id')->nullable()->constrained('membros')->onDelete('set null');
            $table->string('tema')->nullable();
            $table->text('observacoes')->nullable();
            $table->integer('quantidade_presentes')->default(0)->comment('Número de participantes no encontro');
            $table->timestamps();
        });

        Schema::create('presentes_encontros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encontro_id')->constrained('encontros_celulas')->onDelete('cascade');
            $table->foreignId('membro_id')->constrained('membros')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presentes_encontros');
        Schema::dropIfExists('encontros_celulas');
    }
};
