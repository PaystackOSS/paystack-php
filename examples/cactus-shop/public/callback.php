<?php

require_once __DIR__ . '/../src/helpers.php';

$reference = $_GET['reference'] ?? '';
$status = 'unknown';
$message = '';
$transactionData = null;

if (empty($reference)) {
    $status = 'error';
    $message = 'No transaction reference provided.';
} else {
    try {
        $paystack = getPaystack();
        $transaction = $paystack->transaction->verify(['reference' => $reference]);
        
        if ($transaction->data->status === 'success') {
            $status = 'success';
            $message = 'Payment successful! Your cactus is on its way 🌵';
            $transactionData = $transaction->data;
        } else {
            $status = 'failed';
            $message = 'Payment was not successful. Status: ' . $transaction->data->status;
        }
    } catch (\Yabacon\Paystack\Exception\ApiException $e) {
        $status = 'error';
        $message = 'Verification failed: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment <?= ucfirst($status) ?> - Cactus Shop</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: linear-gradient(135deg, #1a472a 0%, #2d5a27 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card { background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); max-width: 500px; width: 100%; padding: 40px; text-align: center; }
        .icon { font-size: 64px; margin-bottom: 20px; }
        .icon.success { color: #2d5a27; }
        .icon.failed, .icon.error { color: #c00; }
        h1 { margin-bottom: 16px; color: #333; }
        .message { color: #666; margin-bottom: 30px; line-height: 1.6; }
        .details { background: #f9f9f9; border-radius: 8px; padding: 20px; text-align: left; margin-bottom: 30px; }
        .details h3 { color: #333; margin-bottom: 12px; font-size: 14px; text-transform: uppercase; }
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #999; }
        .detail-value { color: #333; font-weight: 500; }
        .btn { display: inline-block; background: #2d5a27; color: white; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: bold; }
        .btn:hover { background: #1a472a; }
    </style>
</head>
<body>
    <div class="card">
        <?php if ($status === 'success'): ?>
            <div class="icon success">✓</div><h1>Thank You!</h1>
        <?php elseif ($status === 'failed'): ?>
            <div class="icon failed">✗</div><h1>Payment Failed</h1>
        <?php else: ?>
            <div class="icon error">⚠</div><h1>Error</h1>
        <?php endif; ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php if ($transactionData): ?>
            <div class="details">
                <h3>Transaction Details</h3>
                <div class="detail-row"><span class="detail-label">Reference</span><span class="detail-value"><?= htmlspecialchars($transactionData->reference) ?></span></div>
                <div class="detail-row"><span class="detail-label">Amount</span><span class="detail-value">₦<?= number_format($transactionData->amount / 100, 2) ?></span></div>
                <div class="detail-row"><span class="detail-label">Email</span><span class="detail-value"><?= htmlspecialchars($transactionData->customer->email) ?></span></div>
                <div class="detail-row"><span class="detail-label">Paid At</span><span class="detail-value"><?= date('M j, Y g:i A', strtotime($transactionData->paid_at)) ?></span></div>
            </div>
        <?php endif; ?>
        <a href="/" class="btn">Back to Shop</a>
    </div>
</body>
</html>
