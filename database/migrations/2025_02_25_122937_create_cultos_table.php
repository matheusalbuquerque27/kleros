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
            $table->integer('quant_visitantes')->nullable();
            $table->foreignId('evento_id')->nullable()->constrained('eventos')->onDelete('set null');
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
