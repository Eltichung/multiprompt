<?php

namespace App\Http\Controllers;

use App\Http\Service\ChatService;
use App\Models\AiProvider;
use App\Models\Prompt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
  public function index()
  {
    return view('chat.index', [
      'dataListAIProvider' => AiProvider::getListAIProvider(),
      'dataListPromptHistories' => collect()
    ]);
  }
  public function getData(Request $request)
  {
    $dataListPromptHistories = collect();
    $dataListPromptHistories = ChatService::getChatHistories(1);
//    if ($request->ajax()) {
//      $userId = Auth::id();
//
//      if (!$userId) {
//        return response()->json([
//          'success' => false,
//          'message' => 'Unauthenticated'
//        ], 401);
//      }
//         $dataListPromptHistories = ChatService::getChatHistories($userId);
//    }

    return response()->json([
      'success' => true,
      'dataListPromptHistories' => $dataListPromptHistories,
    ]);
  }
  public function sendMessage(Request $request)
  {
    $userId = Auth::id();

    Prompt::create([
      'user_id' => $userId,
      'content' => $request->message,
    ]);

    // ❌ Xoá cache cũ
    Cache::forget("chat_histories_user_{$userId}");

    // ✅ (Optional) tạo cache mới luôn
    ChatService::getChatHistories($userId);

    return response()->json([
      'success' => true
    ]);
  }
}
