<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAiKey extends Model
{
    protected $table = 'user_ai_keys';
    protected $fillable = [
        'user_id', 'ai_provider_id', 'api_key_encrypted',
        'api_key_last4', 'is_active', 'quota_limit', 'expires_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function provider()
    {
        return $this->belongsTo(AiProvider::class, 'ai_provider_id');
    }

    public function responses()
    {
        return $this->hasMany(AiResponse::class);
    }
}
