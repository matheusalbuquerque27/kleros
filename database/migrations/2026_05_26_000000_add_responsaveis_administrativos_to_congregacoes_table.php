<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('congregacoes', 'responsaveis_administrativos')) {
            return;
        }

        Schema::table('congregacoes', function (Blueprint $table) {
            $table->json('responsaveis_administrativos')
                ->nullable()
                ->after('responsavel_principal_id');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('congregacoes', 'responsaveis_administrativos')) {
            return;
        }

        Schema::table('congregacoes', function (Blueprint $table) {
            $table->dropColumn('responsaveis_administrativos');
        });
    }
};
