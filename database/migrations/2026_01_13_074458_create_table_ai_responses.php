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
        Schema::create('ai_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prompt_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('ai_provider_id')
                ->constrained()
                ->cascadeOnDelete();

            // Nullable vì có thể dùng system key
            $table->foreignId('user_ai_key_id')
                ->nullable()
                ->constrained('user_ai_keys')
                ->nullOnDelete();

            $table->longText('response_text')->nullable();
            $table->unsignedInteger('latency_ms')->nullable();

            $table->enum('status', [
                'success',
                'failed',
                'timeout'
            ]);

            $table->text('error_message')->nullable();

            $table->timestamps();

            $table->index(['prompt_id', 'ai_provider_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_ai_responses');
    }
};
