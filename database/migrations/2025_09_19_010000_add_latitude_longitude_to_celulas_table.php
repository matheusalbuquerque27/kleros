<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('celulas', function (Blueprint $table) {
            if (!Schema::hasColumn('celulas', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('descricao');
            }
            if (!Schema::hasColumn('celulas', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('celulas', function (Blueprint $table) {
            if (Schema::hasColumn('celulas', 'latitude')) {
                $table->dropColumn('latitude');
            }
            if (Schema::hasColumn('celulas', 'longitude')) {
                $table->dropColumn('longitude');
            }
        });
    }
};
