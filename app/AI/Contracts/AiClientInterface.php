<?php

namespace App\AI\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface AiClientInterface
{
    public function sendPrompt(Collection $prompt): array;

}


