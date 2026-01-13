<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiProvider extends Model
{
    //
    protected $fillable = [
        'code', 'name', 'base_url', 'system_api_key_encrypted',
        'system_api_key_last4', 'allow_system_key', 'is_active', 'timeout_sec'
    ];
    public function userAiKeys()
    {
        return $this->hasMany(UserAiKey::class);
    }

    public function responses()
    {
        return $this->hasMany(AiResponse::class);
    }
}
