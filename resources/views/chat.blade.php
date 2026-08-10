<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Donor FAQ Chatbot</title>
    <style>
        :root {
            --bg: #f3f6f4;
            --surface: #ffffff;
            --ink: #1c2b24;
            --muted: #5b6b63;
            --line: #d7e0db;
            --accent: #1f6f4a;
            --accent-soft: #e6f3ec;
            --bot: #eef3f0;
            --user: #1f6f4a;
            --danger: #9b2c2c;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, #dceee4 0%, transparent 45%),
                linear-gradient(180deg, #f7faf8 0%, var(--bg) 100%);
        }

        .page {
            width: min(920px, calc(100% - 2rem));
            margin: 0 auto;
            padding: 2rem 0 3rem;
        }

        h1 {
            margin: 0 0 0.35rem;
            font-size: clamp(1.6rem, 3vw, 2rem);
            letter-spacing: -0.02em;
        }

        .lede {
            margin: 0 0 1.5rem;
            color: var(--muted);
            max-width: 42rem;
            line-height: 1.5;
        }

        .layout {
            display: grid;
            gap: 1rem;
            grid-template-columns: 1.4fr 0.9fr;
        }

        @media (max-width: 800px) {
            .layout { grid-template-columns: 1fr; }
        }

        .panel {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(28, 43, 36, 0.05);
        }

        .chat {
            display: flex;
            flex-direction: column;
            min-height: 560px;
        }

        .messages {
            flex: 1;
            overflow-y: auto;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .bubble {
            max-width: 85%;
            padding: 0.85rem 1rem;
            border-radius: 14px;
            line-height: 1.45;
            white-space: pre-wrap;
        }

        .bubble.bot {
            align-self: flex-start;
            background: var(--bot);
            border-bottom-left-radius: 4px;
        }

        .bubble.user {
            align-self: flex-end;
            background: var(--user);
            color: #fff;
            border-bottom-right-radius: 4px;
        }

        .bubble.error {
            align-self: stretch;
            background: #fdeeee;
            color: var(--danger);
            border: 1px solid #f2c7c7;
        }

        .bubble.typing {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.95rem 1.1rem;
            min-height: 2.75rem;
        }

        .typing-dot {
            width: 0.45rem;
            height: 0.45rem;
            border-radius: 50%;
            background: var(--muted);
            animation: typing-bounce 1.2s ease-in-out infinite;
        }

        .typing-dot:nth-child(2) { animation-delay: 0.15s; }
        .typing-dot:nth-child(3) { animation-delay: 0.3s; }

        @keyframes typing-bounce {
            0%, 60%, 100% {
                transform: translateY(0);
                opacity: 0.4;
            }
            30% {
                transform: translateY(-0.35rem);
                opacity: 1;
            }
        }

        .composer {
            display: flex;
            gap: 0.75rem;
            padding: 1rem;
            border-top: 1px solid var(--line);
        }

        .composer input {
            flex: 1;
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 0.85rem 1.1rem;
            font: inherit;
            outline: none;
            background: #fbfcfb;
        }

        .composer input:focus {
            border-color: #8fbfa5;
            box-shadow: 0 0 0 3px rgba(31, 111, 74, 0.12);
        }

        .composer button {
            border: 0;
            border-radius: 999px;
            padding: 0.85rem 1.25rem;
            background: var(--accent);
            color: #fff;
            font: inherit;
            font-weight: 600;
            cursor: pointer;
        }

        .composer button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .side {
            padding: 1.25rem;
        }

        .side h2 {
            margin: 0 0 0.75rem;
            font-size: 1rem;
        }

        .faq-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
        }

        .faq-list button {
            width: 100%;
            text-align: left;
            border: 1px solid var(--line);
            background: var(--accent-soft);
            color: var(--ink);
            border-radius: 10px;
            padding: 0.7rem 0.85rem;
            font: inherit;
            cursor: pointer;
        }

        .faq-list button:hover {
            border-color: #9fcbb4;
        }

        .hint {
            margin-top: 1rem;
            color: var(--muted);
            font-size: 0.9rem;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <main class="page">
        <h1>Donor FAQ Chatbot</h1>
        <p class="lede">Ask donor-related questions. Answers are generated from a small hardcoded FAQ set using Groq’s free-tier API.</p>

        <div class="layout">
            <section class="panel chat">
                <div id="messages" class="messages">
                    <div class="bubble bot">Hi! I can help with donations, receipts, monthly giving, refunds, and other donor FAQs.</div>
                </div>
                <form id="chat-form" class="composer">
                    <input id="message" type="text" name="message" placeholder="Ask a donor question..." autocomplete="off" required maxlength="1000">
                    <button id="send" type="submit">Send</button>
                </form>
            </section>

            <aside class="panel side">
                <h2>Try asking</h2>
                <ul class="faq-list">
                    @foreach ($faqs as $faq)
                        <li>
                            <button type="button" data-question="{{ $faq['question'] }}">{{ $faq['question'] }}</button>
                        </li>
                    @endforeach
                </ul>
                <p class="hint">Set <code>GROQ_API_KEY</code> in your <code>.env</code> file before chatting.</p>
            </aside>
        </div>
    </main>

    <script>
        const form = document.getElementById('chat-form');
        const input = document.getElementById('message');
        const send = document.getElementById('send');
        const messages = document.getElementById('messages');
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        function appendBubble(text, type) {
            const bubble = document.createElement('div');
            bubble.className = `bubble ${type}`;
            bubble.textContent = text;
            messages.appendChild(bubble);
            messages.scrollTop = messages.scrollHeight;
            return bubble;
        }

        function showTyping() {
            const bubble = document.createElement('div');
            bubble.className = 'bubble bot typing';
            bubble.setAttribute('aria-label', 'Assistant is typing');
            bubble.innerHTML = '<span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span>';
            messages.appendChild(bubble);
            messages.scrollTop = messages.scrollHeight;
            return bubble;
        }

        async function ask(message) {
            appendBubble(message, 'user');
            send.disabled = true;
            input.value = '';
            const typing = showTyping();

            try {
                const response = await fetch('/api/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({ message }),
                });

                const data = await response.json();
                typing.remove();

                if (!response.ok) {
                    appendBubble(data.message || 'Something went wrong.', 'error');
                    return;
                }

                appendBubble(data.reply, 'bot');
            } catch (error) {
                typing.remove();
                appendBubble('Network error. Please try again.', 'error');
            } finally {
                send.disabled = false;
                input.focus();
            }
        }

        form.addEventListener('submit', (event) => {
            event.preventDefault();
            const message = input.value.trim();
            if (message.length < 2) return;
            ask(message);
        });

        document.querySelectorAll('[data-question]').forEach((button) => {
            button.addEventListener('click', () => {
                ask(button.dataset.question);
            });
        });
    </script>
</body>
</html>
