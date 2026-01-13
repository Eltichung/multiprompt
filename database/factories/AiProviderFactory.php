<?php

namespace Database\Factories;

use App\Models\AiProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AiProvider>
 */
class AiProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = AiProvider::class;

    public function definition()
    {
        return [
            'code' => $this->faker->unique()->word(),
            'name' => $this->faker->company(),
            'base_url' => $this->faker->url(),
            'system_api_key_encrypted' => null,
            'system_api_key_last4' => null,
            'allow_system_key' => true,
            'is_active' => true,
            'timeout_sec' => 30,
        ];
    }
}
