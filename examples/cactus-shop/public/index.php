<?php

require_once __DIR__ . '/../src/helpers.php';

$error = '';
$cactusPrice = 500000; // ₦5,000 in kobo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    
    if (!$email) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            $paystack = getPaystack();
            $reference = generateReference();
            
            $transaction = $paystack->transaction->initialize([
                'amount' => $cactusPrice,
                'email' => $email,
                'reference' => $reference,
                'callback_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/callback.php',
                'metadata' => [
                    'product' => 'Desert Cactus',
                    'quantity' => 1
                ]
            ]);
            
            header('Location: ' . $transaction->data->authorization_url);
            exit;
            
        } catch (\Yabacon\Paystack\Exception\ApiException $e) {
            $error = 'Payment initialization failed: ' . $e->getMessage();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cactus Shop - Checkout</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1a472a 0%, #2d5a27 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 400px;
            width: 100%;
            overflow: hidden;
        }
        .product-image {
            background: #f5f5f5;
            padding: 40px;
            text-align: center;
            font-size: 80px;
        }
        .product-info { padding: 30px; }
        h1 { color: #1a472a; margin-bottom: 8px; }
        .price { font-size: 28px; color: #2d5a27; font-weight: bold; margin-bottom: 20px; }
        .description { color: #666; margin-bottom: 24px; line-height: 1.6; }
        .error { background: #fee; color: #c00; padding: 12px; border-radius: 8px; margin-bottom: 16px; }
        form { display: flex; flex-direction: column; gap: 16px; }
        input[type="email"] { padding: 14px; border: 2px solid #ddd; border-radius: 8px; font-size: 16px; }
        input[type="email"]:focus { outline: none; border-color: #2d5a27; }
        button { background: #2d5a27; color: white; border: none; padding: 16px; border-radius: 8px; font-size: 18px; font-weight: bold; cursor: pointer; }
        button:hover { background: #1a472a; }
        .powered-by { text-align: center; padding: 16px; color: #999; font-size: 12px; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="card">
        <div class="product-image">🌵</div>
        <div class="product-info">
            <h1>Desert Cactus</h1>
            <div class="price">₦5,000</div>
            <p class="description">A beautiful low-maintenance succulent perfect for your home or office.</p>
            <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <form method="POST">
                <input type="email" name="email" placeholder="Enter your email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <button type="submit">Buy Now 🌵</button>
            </form>
        </div>
        <div class="powered-by">Powered by Paystack</div>
    </div>
</body>
</html>
