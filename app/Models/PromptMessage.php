<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

}
