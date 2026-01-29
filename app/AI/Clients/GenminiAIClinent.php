<?php

namespace App\AI\Clients;

use App\AI\Contracts\AiClientInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Object_;
use Illuminate\Database\Eloquent\Collection;
class GenminiAIClinent implements AiClientInterface
{
    protected string $apiKey;
    protected string $model;

    public function __construct(string $apiKey, string $model = 'gemini-2.5-flash')
    {
        $this->apiKey = $apiKey;
        $this->model = $model;
    }

    public function sendPrompt(Collection $prompt): array
    {
        $contents = [];
        foreach ($prompt as $message) {
            $contents[] = [
                'role' => $message->role === 'assistant' ? 'model' : 'user',
                'parts' => [
                    ['text' => $message->content],
                ],
            ];
        }
        $messageError = '';
        $keyResponse = 'candidates.0.content.parts.0.text';
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'User-Agent'   => 'Laravel GeminiClient',
            'x-goog-api-key' => $this->apiKey
        ])
            ->timeout(60)
            ->post(
                'https://generativelanguage.googleapis.com/v1/models/'. $this->model.':generateContent',
                [
                    "contents" => $contents
                ]
            );

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



