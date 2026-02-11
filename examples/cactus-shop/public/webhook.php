<?php

require_once __DIR__ . '/../src/helpers.php';

$logFile = __DIR__ . '/../webhook.log';

function logWebhook(string $message): void {
    global $logFile;
    file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] $message\n", FILE_APPEND);
}

http_response_code(200);

$event = \Yabacon\Paystack\Event::capture();
logWebhook("Received webhook event");

$secretKey = getSecretKey();
if (!$event->validFor($secretKey)) {
    logWebhook("ERROR: Invalid signature");
    exit;
}

logWebhook("Signature valid");
$eventType = $event->obj->event ?? 'unknown';
logWebhook("Event type: $eventType");

switch ($eventType) {
    case 'charge.success':
        $data = $event->obj->data;
        $reference = $data->reference ?? 'unknown';
        $amount = ($data->amount ?? 0) / 100;
        $email = $data->customer->email ?? 'unknown';
        logWebhook("SUCCESS: Payment of ₦$amount received from $email (ref: $reference)");
        break;
    case 'charge.failed':
        $reference = $event->obj->data->reference ?? 'unknown';
        logWebhook("FAILED: Payment failed for reference $reference");
        break;
    default:
        logWebhook("Unhandled event type: $eventType");
}

logWebhook("Webhook processing complete");
