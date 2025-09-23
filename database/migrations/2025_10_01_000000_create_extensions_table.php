<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('extensoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('congregacao_id')->nullable()->constrained('congregacoes')->onDelete('cascade');
            $table->string('module', 120);
            $table->boolean('enabled')->default(true);
            $table->json('options')->nullable();
            $table->timestamps();

            $table->unique(['congregacao_id', 'module']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extensoes');
    }
};
