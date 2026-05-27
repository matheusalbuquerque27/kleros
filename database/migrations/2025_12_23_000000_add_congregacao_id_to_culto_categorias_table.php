<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $defaultCongregacaoId = DB::table('congregacoes')->min('id');
        $driver = DB::getDriverName();

        if (! Schema::hasColumn('culto_categorias', 'congregacao_id')) {
            Schema::table('culto_categorias', function (Blueprint $table) {
                $table->unsignedBigInteger('congregacao_id')
                    ->nullable()
                    ->after('id');
            });

            if ($defaultCongregacaoId) {
                DB::table('culto_categorias')->update(['congregacao_id' => $defaultCongregacaoId]);
            }
        }

        if ($defaultCongregacaoId) {
            DB::table('culto_categorias')
                ->whereNull('congregacao_id')
                ->update(['congregacao_id' => $defaultCongregacaoId]);

            $congregacaoIds = DB::table('congregacoes')->pluck('id')->all();
            if (! empty($congregacaoIds)) {
                DB::table('culto_categorias')
                    ->whereNotIn('congregacao_id', $congregacaoIds)
                    ->update(['congregacao_id' => $defaultCongregacaoId]);
            }
        }

        $foreignExists = false;

        if ($driver === 'mysql') {
            $foreignExists = DB::table('information_schema.KEY_COLUMN_USAGE')
                ->where('TABLE_SCHEMA', DB::getDatabaseName())
                ->where('TABLE_NAME', 'culto_categorias')
                ->where('COLUMN_NAME', 'congregacao_id')
                ->where('REFERENCED_TABLE_NAME', 'congregacoes')
                ->exists();
        }

        if (! $foreignExists) {
            Schema::table('culto_categorias', function (Blueprint $table) {
                $table->foreign('congregacao_id')
                    ->references('id')
                    ->on('congregacoes')
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('culto_categorias', 'congregacao_id')) {
            Schema::table('culto_categorias', function (Blueprint $table) {
                $table->dropForeign(['congregacao_id']);
                $table->dropColumn('congregacao_id');
            });
        }
    }
};
