<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('metadata', function (Blueprint $table) {
            $table->string('key', 50)->primary();
            $table->string('value', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metadata');
    }
};