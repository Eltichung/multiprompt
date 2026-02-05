<?php

namespace App\AI;

use App\AI\Contracts\AiClientInterface;
use App\AI\Clients\OpenAiClient;
use App\AI\Clients\GenminiAIClinent;
use App\Models\AiProvider;
use App\Models\UserAiKey;
use Illuminate\Support\Facades\Log;
use function Pest\Laravel\json;

class AiClientFactory
{
    public static function callAIByProvider(AiProvider $provider, ?UserAiKey $userKey = null): AiClientInterface {
        try {

            // Lấy API key (ưu tiên user, fallback system)
            $apiKey = decrypt($provider->system_api_key_encrypted);

            return match ($provider->code) {
                AiProvider::OPEN_AI_CODE => new OpenAiClient($apiKey),
                AiProvider::GEMINI_AI_CODE => new GenminiAIClinent($apiKey),
                default => throw new \InvalidArgumentException(
                    'Unsupported AI provider: ' . $provider->code
                ),
            };

        } catch (\Exception $e) {
          Log::error("[callAIByProvider] Exception {$e->getMessage()}, on file {$e->getFile()}, on line {$e->getLine()}");
          throw $e;
        }
    }
}
