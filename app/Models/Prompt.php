<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prompt extends Model
{
    protected $table = 'prompts';
    protected $fillable = [
        'user_id', 'content', 'status'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function responses()
    {
        return $this->hasMany(AiResponse::class);
    }
}
