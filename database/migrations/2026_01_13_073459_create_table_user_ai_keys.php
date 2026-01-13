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
        Schema::create('user_ai_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('ai_provider_id')
                ->constrained()
                ->cascadeOnDelete();

            // USER API KEY
            $table->text('api_key_encrypted');
            $table->string('api_key_last4', 4);

            $table->boolean('is_active')->default(true);

            // Optional limits
            $table->unsignedInteger('quota_limit')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            // 1 user = 1 key / provider (MVP)
            $table->unique(['user_id', 'ai_provider_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_user_ai_key');
    }
};
