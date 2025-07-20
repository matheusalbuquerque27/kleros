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
        Schema::create('bases_doutrinarias', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('denominacoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('base_doutrinaria')->nullable()->constrained('bases_doutrinarias')->onDelete('set null');
            $table->boolean('ativa')->default(true);
            $table->json('ministerios_eclesiasticos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('denominacaos');
        Schema::dropIfExists('bases_doutrinarias');
    }
};
