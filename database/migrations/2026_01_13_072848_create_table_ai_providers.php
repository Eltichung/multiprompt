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
        Schema::create('ai_providers', function (Blueprint $table) {
            $table->id();

            // Provider info
            $table->string('code')->unique(); // openai, gemini, claude
            $table->string('name');
            $table->string('base_url')->nullable();

            // SYSTEM API KEY (ADMIN)
            $table->text('system_api_key_encrypted')->nullable();
            $table->string('system_api_key_last4', 4)->nullable();

            // Config
            $table->boolean('allow_system_key')->default(true);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('timeout_sec')->default(15);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_ai_providers');
    }
};
