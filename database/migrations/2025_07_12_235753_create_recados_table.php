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
        Schema::create('recados', function (Blueprint $table) {
            $table->id();
            $table->text('mensagem');
            $table->date('data_recado');
            $table->boolean('status');
            $table->foreignId('congregacao_id')->constrained('congregacoes')->onDelete('cascade');
            $table->foreignId('culto_id')->constrained('cultos')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recados');
    }
};
