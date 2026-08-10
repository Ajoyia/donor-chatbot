<?php

return [
    'provider' => env('CHATBOT_PROVIDER', 'groq'),

    'groq' => [
        'api_key' => env('GROQ_API_KEY'),
        'base_url' => env('GROQ_BASE_URL', 'https://api.groq.com/openai/v1'),
        'model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
        'timeout' => (int) env('GROQ_TIMEOUT', 30),
    ],

    'system_prompt' => 'You are a helpful donor support assistant for a nonprofit organization. Answer questions using only the FAQ context provided below. If the question is unrelated to donations or not covered by the FAQ, politely say you can only help with donor-related questions and suggest contacting support@example.org. Keep answers concise and friendly.',

    'faqs' => [
        [
            'question' => 'How can I make a donation?',
            'answer' => 'You can donate online through our website using a credit or debit card, bank transfer, or PayPal. Monthly recurring donations are also available from your donor dashboard.',
        ],
        [
            'question' => 'Is my donation tax-deductible?',
            'answer' => 'Yes. Donations to our registered nonprofit are tax-deductible to the extent allowed by law. A donation receipt is emailed automatically after each gift.',
        ],
        [
            'question' => 'How do I get a donation receipt?',
            'answer' => 'Receipts are sent to the email address used at checkout. You can also download past receipts anytime from your donor account under Giving History.',
        ],
        [
            'question' => 'Can I set up a monthly donation?',
            'answer' => 'Yes. Choose the monthly option on the donation form. You can pause, change the amount, or cancel recurring gifts from your donor dashboard at any time.',
        ],
        [
            'question' => 'How are donations used?',
            'answer' => 'At least 85% of every donation goes directly to programs. The remainder covers essential operations such as fundraising, administration, and donor support.',
        ],
        [
            'question' => 'Can I donate in honor or memory of someone?',
            'answer' => 'Yes. On the donation form, select Tribute Gift and enter the honoree details. We can notify their family or friends with a message you provide.',
        ],
        [
            'question' => 'What payment methods do you accept?',
            'answer' => 'We accept Visa, Mastercard, American Express, Discover, ACH bank transfer, and PayPal. Corporate gifts via check or wire transfer can be arranged by contacting donor services.',
        ],
        [
            'question' => 'How do I update my donor profile or payment method?',
            'answer' => 'Sign in to your donor account, open Profile or Payment Methods, and update your details. Changes take effect on the next scheduled donation.',
        ],
        [
            'question' => 'Can I cancel or refund a donation?',
            'answer' => 'One-time donations can usually be refunded within 30 days if requested in writing. Recurring donations can be cancelled anytime; refunds for processed payments are reviewed case by case.',
        ],
        [
            'question' => 'How do I contact donor support?',
            'answer' => 'Email support@example.org or call 1-800-555-0199 Monday through Friday, 9am to 5pm ET. Average response time is one business day.',
        ],
    ],
];
