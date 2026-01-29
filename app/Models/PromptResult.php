<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class PromptResult extends Model
{
    protected $table = 'prompt_results';
    protected $fillable = [
        'prompt_id', 'ai_provider_id', 'status', 'response','error'
    ];
    public function prompt()
    {
        return $this->belongsTo(Prompt::class);
    }

    public function provider()
    {
        return $this->belongsTo(AiProvider::class, 'ai_provider_id');
    }
    public static function getLatestByPrompt(int $promptId)
    {
        return self::where('prompt_id', $promptId)
            ->orderByDesc('id')
            ->first();
    }
}
