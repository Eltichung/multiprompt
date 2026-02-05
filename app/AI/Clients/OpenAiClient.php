<?php
namespace App\AI\Clients;

use App\AI\Contracts\AiClientInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
class OpenAiClient implements AiClientInterface
{
    protected string $apiKey;
    protected string $model;

    public function __construct(string $apiKey, string $model = 'gpt-5-nano')
    {
        $this->apiKey = $apiKey;
        $this->model = $model;
    }

    public function sendPrompt(Collection $prompt): array
    {
        $messageError = '';
        $keyResponse = 'output.1.content.0.text';
        $contents = [];
        foreach ($prompt as $message) {
            $contents[] = [
                'role' => $message->role,
                'content' => [
                    [   'type' => $message->role === 'user' ?'input_text':'output_text',
                        'text' => $message->content],
                ],
            ];
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])
            ->timeout(60)
            ->post('https://api.openai.com/v1/responses', [
                'model' => $this->model,
                'input' => $contents,
                'store' => true,
            ]);

        if ($response->failed()) {
            Log::error('Open AI API error', [
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
