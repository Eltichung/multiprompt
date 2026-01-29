<?php

namespace App\Http\Service;

use App\Models\Prompt;
use Illuminate\Support\Facades\Cache;

class ChatService
{
  public static function getChatHistories(int $userId)
  {
    $cacheKey = "chat_histories_user_{$userId}";

    return Cache::remember(
      $cacheKey,
      now()->addMinutes(30),
      function () use ($userId) {
        return Prompt::getListPromptHistories($userId);
      }
    );
  }
}
