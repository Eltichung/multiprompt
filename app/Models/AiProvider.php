<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AiProvider extends Model
{
    use HasFactory;
    protected $table = 'ai_providers';
    const OPEN_AI_CODE = 'openai';
    const GEMINI_AI_CODE = 'gemini';

    protected $fillable = [
        'code', 'name', 'base_url', 'system_api_key_encrypted',
        'system_api_key_last4', 'allow_system_key', 'is_active', 'timeout_sec'
    ];

    public static function getAiProvider($provider)
    {
        return self::where('code', $provider)->first();

    }
    public function userAiKeys()
    {
        return $this->hasMany(UserAiKey::class);
    }

    public function responses()
    {
        return $this->hasMany(AiResponse::class);
    }
    public static function getListAIProvider()
    {
        try {
          return self::select('code','name')->get();
        } catch (\Exception $e) {
            Log::error("[getListAIProvider] Exception {$e->getMessage()}, on file {$e->getFile()}, on line {$e->getLine()}");
            return collect();
        }
    }

}
