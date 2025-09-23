<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('verses', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->tinyInteger('book_id')->unsigned()->nullable();
            $table->smallInteger('chapter')->nullable();
            $table->smallInteger('verse')->nullable();
            $table->text('text')->nullable();

            // Se quiser relacionar
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verses');
    }
};