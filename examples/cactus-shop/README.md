# Cactus Shop - Paystack PHP Sample Application

A minimal checkout demo testing the `paystack-php` library.

## Setup

1. Copy and configure environment:
   ```bash
   cp .env.example .env
   # Edit .env with your Paystack test keys
   ```

2. Start the server:
   ```bash
   php -S localhost:8000 -t public
   ```

3. Open http://localhost:8000

## Testing Webhooks

1. Start ngrok: `ngrok http 8000`
2. Set webhook URL in Paystack Dashboard: `https://your-ngrok-url/webhook.php`
3. Webhook events are logged to `webhook.log`

## Test Cards

| Card Number | CVV | Result |
|-------------|-----|--------|
| 4084 0841 0841 0841 | 408 | Success |
| 4084 0841 0841 0842 | 408 | Failed |

PIN: 0000 | OTP: 123456
