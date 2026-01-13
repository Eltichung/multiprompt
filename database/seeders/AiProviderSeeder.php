<?php

namespace Database\Seeders;

use App\Models\AiProvider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AiProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AiProvider::create([
            'code' => 'openai',
            'name' => 'OpenAI',
            'base_url' => 'https://api.openai.com/v1',
            'system_api_key_encrypted' => null,
            'system_api_key_last4' => null,
            'allow_system_key' => true,
            'is_active' => true,
            'timeout_sec' => 30,
        ]);

        AiProvider::create([
            'code' => 'claude',
            'name' => 'Anthropic Claude',
            'base_url' => 'https://api.anthropic.com/v1',
            'system_api_key_encrypted' => null,
            'system_api_key_last4' => null,
            'allow_system_key' => true,
            'is_active' => true,
            'timeout_sec' => 30,
        ]);
    }
}
