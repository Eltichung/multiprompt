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
        Schema::create('prompt_messages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('prompt_id')
                ->constrained('prompts')
                ->cascadeOnDelete();

            $table->enum('role', ['user', 'assistant']);

            $table->text('content');

            $table->foreignId('ai_provider_id')
                ->nullable()
                ->constrained('ai_providers')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabel_prompt_messages');
    }
};
