<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class PromptMessage extends Model
{
    protected $fillable = [
        'prompt_id',
        'role',
        'content',
        'ai_provider_id',
    ];

    public function prompt()
    {
        return $this->belongsTo(Prompt::class);
    }

    public function provider()
    {
        return $this->belongsTo(AiProvider::class, 'ai_provider_id');
    }
    public function promptRessult()
    {
      return $this->belongsTo(PromptResult::class, 'prompt_id');
    }
  public static function getPromptMessageByID($promptID)
  {
    try {
      return self::with(['provider:id,code,name'])
                  ->where('prompt_id',$promptID)
                  ->get()
                  ->groupBy('ai_provider_id')
                  ->values();
    } catch (\Exception $e) {
      Log::error("[getListPromptHistories] Exception {$e->getMessage()}, on file {$e->getFile()}, on line {$e->getLine()}");
      return collect();
    }
  }
}
