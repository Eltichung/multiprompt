<?php

namespace App\Http\Controllers;

use App\Http\Service\ChatService;
use App\Models\AiProvider;
use App\Models\Prompt;
use App\Models\PromptMessage;
use App\Models\PromptResult;
use App\Models\User;
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
      'dataListPromptHistories' => collect(),
      'dataUser' => User::findOrFail(Auth::id())

    ]);
  }
  public function getDataChatHistories(Request $request)
  {
    $dataListPromptHistories = collect();
    if ($request->ajax()) {
      $userId = Auth::id();

      if (!$userId) {
        return response()->json([
          'success' => false,
          'message' => 'Unauthenticated'
        ], 401);
      }
         $dataListPromptHistories = ChatService::getChatHistories($userId);
    }

    return response()->json([
      'success' => true,
      'data' => $dataListPromptHistories,
    ]);
  }
  public function getDataPrompt(Request $request){
    $dataRequest= $request->only(['prompt_id']);
    $validatorData = Validator::make($dataRequest, [
      'prompt_id' => 'required|int',
    ]);
    if ($validatorData->fails()) {
          return  response()->json([
              'message' => 'Prompt_id is required',
          ]);
      }
    $data = PromptMessage::getPromptMessageByID($dataRequest['prompt_id']);
    $dataPromptResults = PromptResult::getLatestByPrompt($dataRequest['prompt_id']);
    foreach ($data as $providerId => $messages) {
      foreach ($dataPromptResults as $dataPromptResult) {
        if ($dataPromptResult['ai_provider_id'] == $messages[0]['ai_provider_id']) {
          $messages[0]['latest_result'] = $dataPromptResult;
          break;
        }
      }
    }

    return response()->json([
      'success' => true,
      'data' => $data,
    ]);

  }
}
