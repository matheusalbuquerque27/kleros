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
        Schema::create('cultos', function (Blueprint $table) {
            $table->id();
            $table->date('data_culto');
            $table->string('preletor')->nullable();
            $table->string('tema_sermao')->nullable();
            $table->string('texto_base')->nullable();
            $table->integer('quant_visitantes')->nullable();
            $table->integer('quant_adultos')->nullable();
            $table->integer('quant_criancas')->nullable();
            $table->text('observacoes')->nullable();
            $table->foreignId('evento_id')->nullable()->constrained('eventos')->onDelete('set null');
            $table->foreignId('congregacao_id')->nullable()->constrained('congregacoes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cultos');
    }
};
