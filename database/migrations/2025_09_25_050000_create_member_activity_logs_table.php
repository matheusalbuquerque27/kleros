<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('membro_id')->nullable()->constrained('membros')->nullOnDelete();
            $table->string('action');
            $table->string('route')->nullable();
            $table->string('method', 10)->nullable();
            $table->string('url', 2048);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('payload')->nullable();
            $table->nullableMorphs('subject');
            $table->timestamp('logged_at')->useCurrent();
            $table->timestamps();

            $table->index(['action', 'logged_at']);
            $table->index('route');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_activity_logs');
    }
};
