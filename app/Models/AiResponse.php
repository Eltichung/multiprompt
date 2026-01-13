<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiResponse extends Model
{
    protected $fillable = [
        'prompt_id', 'ai_provider_id', 'user_ai_key_id',
        'response_text', 'latency_ms', 'status', 'error_message'
    ];
    public function prompt()
    {
        return $this->belongsTo(Prompt::class);
    }

    public function provider()
    {
        return $this->belongsTo(AiProvider::class, 'ai_provider_id');
    }

    public function userAiKey()
    {
        return $this->belongsTo(UserAiKey::class);
    }
}
