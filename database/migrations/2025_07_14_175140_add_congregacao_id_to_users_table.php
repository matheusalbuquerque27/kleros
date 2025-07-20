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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('denominacao_id')->nullable()->constrained('denominacoes')->onDelete('cascade');
            $table->foreignId('congregacao_id')->nullable()->constrained('congregacoes')->onDelete('cascade');
            $table->foreignId('membro_id')->nullable()->constrained('membros')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['congregacao_id']);
            $table->dropColumn('congregacao_id');
            $table->dropForeign(['denominacao_id']);
            $table->dropColumn('denominacao_id');
        });
    }
};
