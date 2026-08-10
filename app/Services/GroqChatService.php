<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GroqChatService
{
    public function reply(string $message): string
    {
        $apiKey = config('chatbot.groq.api_key');

        if (blank($apiKey)) {
            throw new RuntimeException('GROQ_API_KEY is not configured.');
        }

        $payload = [
            'model' => config('chatbot.groq.model'),
            'temperature' => 0.3,
            'max_tokens' => 500,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $this->systemMessage(),
                ],
                [
                    'role' => 'user',
                    'content' => $message,
                ],
            ],
        ];

        try {
            $response = Http::baseUrl(rtrim(config('chatbot.groq.base_url'), '/'))
                ->withToken($apiKey)
                ->acceptJson()
                ->timeout(config('chatbot.groq.timeout'))
                ->post('/chat/completions', $payload)
                ->throw()
                ->json();
        } catch (RequestException $exception) {
            $detail = data_get($exception->response?->json(), 'error.message', $exception->getMessage());

            throw new RuntimeException('Groq API request failed: '.$detail, previous: $exception);
        }

        $content = data_get($response, 'choices.0.message.content');

        if (! is_string($content) || trim($content) === '') {
            throw new RuntimeException('Groq API returned an empty response.');
        }

        return trim($content);
    }

    private function systemMessage(): string
    {
        $faqLines = collect(config('chatbot.faqs'))
            ->map(fn (array $faq, int $index) => ($index + 1).'. Q: '.$faq['question']."\nA: ".$faq['answer'])
            ->implode("\n\n");

        return config('chatbot.system_prompt')."\n\nFAQ CONTEXT:\n".$faqLines;
    }
}
