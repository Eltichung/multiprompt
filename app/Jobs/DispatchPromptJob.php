<?php

namespace App\Jobs;

use App\AI\AiClientFactory;
use App\Models\Prompt;
use App\Models\PromptMessage;
use App\Models\PromptResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DispatchPromptJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  // THỰC CHẤT là prompt_result.id
  public function __construct(public int $promptId)
  {
  }

  public function handle()
  {
    try {
      // Get PromptResult (AI processing)
      $providerPrompt = PromptResult::with('provider')
        ->findOrFail($this->promptId);

      // Get ID Prompt + message (AI processing)
      $promptInfo = Prompt::with([
        'messages' => fn($q) => $q->orderBy('id'),
      ])->findOrFail($providerPrompt->prompt_id);

      // call AI
      $client = AiClientFactory::callAIByProvider(
        $providerPrompt->provider
      );

      $responseFromAI = $client->sendPrompt(
        $promptInfo->messages
      );

      // Update PromptResult
      $providerPrompt->update([
        'status' => empty($responseFromAI['error']) ? 'success' : 'failed',
        'response' => $responseFromAI['body'] ?? null,
        'error' => $responseFromAI['error'] ?? null,
      ]);
      $promptInfo->update([
        'status' => 'success'
      ]);
      // store conversation when AI response
      if (!empty($responseFromAI['body'])) {
        PromptMessage::create([
          'prompt_id' => $promptInfo->id,
          'role' => 'assistant',
          'ai_provider_id' => $providerPrompt->ai_provider_id,
          'content' => $responseFromAI['body'],
        ]);

      }

    } catch (\Throwable $e) {
      Log::error('DispatchPromptJob failed', [
        'prompt_result_id' => $this->promptId,
        'error' => $e->getMessage(),
      ]);

      // update status record
      PromptResult::where('id', $this->promptId)->update([
        'status' => 'failed',
        'error' => $e->getMessage(),
      ]);
    }
  }
}
