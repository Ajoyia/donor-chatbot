# Donor FAQ Chatbot

A simple Laravel app that answers donor-related FAQs using Groq’s free-tier chat API and a hardcoded FAQ context.

## Features

- Chat UI at `/`
- JSON chat endpoint at `POST /api/chat`
- Hardcoded donor FAQ context in `config/chatbot.php`
- Groq OpenAI-compatible API integration (`llama-3.3-70b-versatile` by default)

## Requirements

- PHP 8.2+
- Composer
- A free [Groq API key](https://console.groq.com/keys)

## Setup

```bash
cd donor-faq-chatbot
composer install
cp .env.example .env
php artisan key:generate
```

Create the SQLite database file if needed:

```bash
# Windows PowerShell
New-Item -ItemType File -Path database\database.sqlite -Force

# macOS / Linux
touch database/database.sqlite
```

```bash
php artisan migrate
```

Add your Groq key to `.env`:

```env
GROQ_API_KEY=your_groq_api_key_here
GROQ_MODEL=llama-3.3-70b-versatile
```

Start the app:

```bash
php artisan serve
```

Open http://127.0.0.1:8000

## API usage

```bash
curl -X POST http://127.0.0.1:8000/api/chat \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"message\":\"How do I get a donation receipt?\"}"
```

Example success response:

```json
{
  "reply": "Receipts are sent to the email address used at checkout..."
}
```

## Project structure

| Path | Purpose |
| --- | --- |
| `config/chatbot.php` | Groq settings + FAQ context |
| `app/Services/GroqChatService.php` | Calls Groq chat completions |
| `app/Http/Controllers/ChatController.php` | UI + API handlers |
| `resources/views/chat.blade.php` | Simple chat interface |
| `routes/web.php` | `/` and `/api/chat` routes |

## Notes

- No paid API is required; Groq free tier is enough for testing.
- The model is instructed to answer only from the FAQ context.
- Update FAQs anytime in `config/chatbot.php`.
