<?php
namespace App\AI\Clients;

use App\AI\Contracts\AiClientInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterAiClient implements AiClientInterface
{
    protected string $apiKey;
    protected string $model;

    public function __construct(string $apiKey, string $model = 'openai/gpt-5.2')
    {
        $this->apiKey = $apiKey;
        $this->model = $model;
    }

    public function sendPrompt(string $prompt): array
    {
        $messageError = '';
        $keyResponse = 'output.1.content.0.text';
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openrouter.key'),
            'Content-Type'  => 'application/json',
            'HTTP-Referer'  => config('app.url'),   // optional
            'X-Title'       => config('app.name'),  // optional
        ])
            ->timeout(60)
            ->post('https://openrouter.ai/v1/chat/completions', [
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'stream' => false,
            ]);

        if ($response->failed()) {
            Log::error('OpenRouter API error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            $messageError = $response->body();
        }
        return [
            'status' => $response->status(),
            'body'   => $response->json($keyResponse) ?? '',
            'error' => $messageError
        ];

    }
}
