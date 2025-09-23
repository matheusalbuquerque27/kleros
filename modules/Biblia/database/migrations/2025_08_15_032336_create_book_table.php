<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->tinyIncrements('id'); // PK auto increment
            $table->tinyInteger('book_reference_id')->nullable();
            $table->tinyInteger('testament_reference_id')->nullable();
            $table->string('name', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
