<?php

namespace App\AI\Clients;

use App\AI\Contracts\AiClientInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClaudeAIClinent implements AiClientInterface
{
    protected string $apiKey;
    protected string $model;

    public function __construct(string $apiKey, string $model = 'gemini-2.5-flashclaude-4')
    {
        $this->apiKey = $apiKey;
        $this->model = $model;
    }

    public function sendPrompt(string $prompt): array
    {
        $messageError = '';
        $keyResponse = 'candidates.0.content.parts.0.text';
        $response = Http::withHeaders([
            'x-api-key' => env('ANTHROPIC_API_KEY'),
            'Content-Type' => 'application/json',
            'anthropic-version' => '2023-06-01' // Cố định phiên bản API
        ])->post('https://api.anthropic.com/v1/messages', [
            'model' => 'claude-4',   // Hoặc model bạn đăng ký
            'messages' => [
                ['role' => 'user', 'content' => 'Vết 3 câu chào bằng 3 thứ tiếng']
            ],
            'max_tokens' => 300
        ]);


        if ($response->failed()) {
            Log::error('Gemini API error', [
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



