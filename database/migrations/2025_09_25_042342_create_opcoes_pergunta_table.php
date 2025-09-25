<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('opcoes_pergunta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pergunta_id')->constrained('perguntas')->onDelete('cascade');
            $table->string('texto');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('opcoes_pergunta');
    }
};
