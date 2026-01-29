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
        Schema::create('prompt_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prompt_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('ai_provider_id')
                ->constrained();

            $table->longText('response')->nullable();

            $table->string('status')
                ->default('pending'); // pending, success, failed

            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabel_prompt_results');
    }
};
