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
        Schema::create('membro_celula', function (Blueprint $table) {
            $table->foreignId('celula_id')->constrained('celulas')->cascadeOnDelete();
            $table->foreignId('membro_id')->constrained('membros')->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['celula_id', 'membro_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membro_celula');
    }
};
