<?php

namespace App\Http\Controllers;

use App\Services\GroqChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class ChatController extends Controller
{
    public function index(): View
    {
        return view('chat', [
            'faqs' => config('chatbot.faqs'),
        ]);
    }

    public function chat(Request $request, GroqChatService $chatService): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'min:2', 'max:1000'],
        ]);

        try {
            $reply = $chatService->reply($validated['message']);
        } catch (RuntimeException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 502);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'Unable to generate a response right now. Please try again.',
            ], 500);
        }

        return response()->json([
            'reply' => $reply,
        ]);
    }
}
