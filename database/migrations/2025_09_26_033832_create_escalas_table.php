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
        Schema::create('escalas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('culto_id')->nullable()->constrained('cultos')->nullOnDelete();
            $table->foreignId('tipo_escala_id')->constrained('tipos_escala')->cascadeOnDelete();
            $table->dateTime('data_hora')->nullable();  // se não for culto
            $table->string('local')->nullable();        // se não for culto
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escalas');
    }
};
