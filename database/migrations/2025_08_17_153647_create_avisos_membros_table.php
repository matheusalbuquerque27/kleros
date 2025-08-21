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
        Schema::create('aviso_membro', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aviso_id');
            $table->unsignedBigInteger('membro_id');
            $table->boolean('lido')->default(false); // opcional: marcar se o usuário já leu
            $table->timestamps();

            $table->foreign('aviso_id')->references('id')->on('avisos')->onDelete('cascade');
            $table->foreign('membro_id')->references('id')->on('membros')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('aviso_membro');
    }
};
