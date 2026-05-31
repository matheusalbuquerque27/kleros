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
        Schema::table('presentes_encontros', function (Blueprint $table) {
            // Torna membro_id opcional (pode ser visitante sem cadastro)
            $table->foreignId('membro_id')->nullable()->change();

            // Campos para visitante não cadastrado
            $table->string('visitante_nome')->nullable()->after('membro_id');
            $table->string('visitante_telefone')->nullable()->after('visitante_nome');
            $table->foreignId('visitante_sit_id')->nullable()->constrained('situacao_visitantes')->after('visitante_telefone');
            $table->text('visitante_observacoes')->nullable()->after('visitante_sit_id');

            // Referência opcional ao visitante já cadastrado na tabela visitantes
            $table->foreignId('visitante_id')->nullable()->constrained('visitantes')->nullOnDelete()->after('visitante_observacoes');
        });
    }

    public function down(): void
    {
        Schema::table('presentes_encontros', function (Blueprint $table) {
            $table->dropConstrainedForeignId('visitante_id');
            $table->dropForeign(['visitante_sit_id']);
            $table->dropColumn(['visitante_nome', 'visitante_telefone', 'visitante_sit_id', 'visitante_observacoes', 'visitante_id']);
            $table->foreignId('membro_id')->nullable(false)->change();
        });
    }
};
