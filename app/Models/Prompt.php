<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Prompt extends Model
{
  protected $table = 'prompts';
  protected $fillable = [
    'user_id', 'content','status'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function responses()
  {
    return $this->hasMany(AiResponse::class);
  }

  public function messages()
  {
    return $this->hasMany(PromptMessage::class);
  }

  public function provider()
  {
    return $this->hasOne(AiProvider::class);
  }

  public static function getListPromptHistories($userID)
  {
    try {
      return self::where('user_id', $userID)
        ->orderByDesc('created_at')
        ->limit(10)
        ->get();
    } catch (\Exception $e) {
      Log::error("[getListPromptHistories] Exception {$e->getMessage()}, on file {$e->getFile()}, on line {$e->getLine()}");
      return collect();
    }
  }
}
