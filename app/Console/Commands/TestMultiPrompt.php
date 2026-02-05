<?php

namespace App\Console\Commands;

use App\Http\Controllers\PromptController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Console\Command;

class TestMultiPrompt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multiprompt:test_submit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
//        dd(encrypt('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'));


        $user = User::first();

        // Fake request data
        $request = Request::create(
            '/prompts',
            'POST',
            [
                'user_id' => $user->id,
                'prompt' => 'dịch sang tiếng nhật',
                'providers' => ['openai','gemini'],
                 'prompt_id' => 136,
                'ai_provider_id' => 2// provider ID có sẵn
            ]
        );

        // Fake user login
        auth()->login($user);

        // Gọi controller
        $controller = app(PromptController::class);

        $response = $controller->submit($request);


    }
}
