<?php

require_once __DIR__ . '/../../../src/autoload.php';

function loadEnv(): void
{
    $envFile = __DIR__ . '/../.env';
    if (!file_exists($envFile)) {
        die('Error: .env file not found. Copy .env.example to .env and add your Paystack keys.');
    }

    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

function getPaystack(): \Yabacon\Paystack
{
    loadEnv();
    $secretKey = $_ENV['PAYSTACK_SECRET_KEY'] ?? '';
    
    if (empty($secretKey) || $secretKey === 'sk_test_xxxxxxxxxxxxxxxxxxxxxxxxxxxxx') {
        die('Error: Please set your PAYSTACK_SECRET_KEY in .env file');
    }
    
    return new \Yabacon\Paystack($secretKey);
}

function getSecretKey(): string
{
    loadEnv();
    return $_ENV['PAYSTACK_SECRET_KEY'] ?? '';
}

function generateReference(): string
{
    return 'CACTUS_' . time() . '_' . bin2hex(random_bytes(4));
}
