<?php

namespace App\Http\Controllers;

use App\Http\Service\ChatService;
use App\Models\AiProvider;
use App\Models\Prompt;
use App\Models\PromptMessage;
use App\Models\PromptResult;
use App\Models\Transactions;
use Carbon\Carbon;
use App\Jobs\DispatchPromptJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class PromptController extends Controller
{
  public function submit(Request $request)
  {
    $dataPrompt = $request->only(['prompt', 'providers', 'prompt_id']);
    $validatorData = Validator::make($dataPrompt, [
      'prompt' => 'required|string',
      'providers' => 'required|array|min:1',
    ]);
    /*  if ($validatorData->fails()) {
          return  response()->json([

              'message' => 'Prompt sent to multiple AI providers',
          ]);
      }*/
    // prompt
    $prompt = null;
    if (!empty($dataPrompt['prompt_id'])) {
      $prompt = Prompt::findOrFail($dataPrompt['prompt_id']);
    } else {
      $prompt = Prompt::create([
        'user_id' => Auth::id(),
        'content' => $request->prompt,
        'status' => 'pending'
      ]);
    }


    // prompt result
    foreach ($request->providers as $providerId) {
      $provider = AiProvider::getAiProvider($providerId);
      PromptMessage::create([
        'prompt_id' => $prompt->id,
        'role' => 'user',
        'ai_provider_id' => $provider->id,
        'content' => $dataPrompt['prompt'],
      ]);
      $result = PromptResult::create([
        'prompt_id' => $prompt->id,
        'ai_provider_id' => $provider->id,
        'status' => 'pending',
      ]);

      DispatchPromptJob::dispatch($result->id);

    }
    $userId = Auth::id();
    Cache::forget("chat_histories_user_{$userId}");
    ChatService::getChatHistories($userId);
    return response()->json([
      'success' => true,
      'data' => [
        'prompt_id' => $prompt->id,
      ],
    ]);
  }

}
