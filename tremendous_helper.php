<?php

/**
 * Simple .env loader (no Composer, no external libs).
 * It will load KEY=VALUE pairs into $_ENV and getenv().
 */
function load_env(string $envPath): void
{
    if (!file_exists($envPath)) {
        return; // silently ignore if no .env
    }

    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        // Skip comments
        if ($line === '' || $line[0] === '#') {
            continue;
        }

        // Skip invalid lines
        if (strpos($line, '=') === false) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);

        $name  = trim($name);
        $value = trim($value);

        // Remove wrapping quotes if present
        $value = trim($value, " \t\n\r\0\x0B\"'");

        // Store in $_ENV and environment
        $_ENV[$name] = $value;
        putenv("$name=$value");
    }
}

/**
 * Get Tremendous API key from .env or environment.
 */
function get_tremendous_api_key(): string
{
    // Ensure .env is loaded once
    static $loaded = false;
    if (!$loaded) {
        load_env(__DIR__ . '/.env');
        $loaded = true;
    }

    $apiKey = $_ENV['TREMENDOUS_API_KEY'] ?? getenv('TREMENDOUS_API_KEY');

    if (empty($apiKey)) {
        throw new Exception('TREMENDOUS_API_KEY is not set in .env or environment');
    }

    return $apiKey;
}

/**
 * Call Tremendous API to send a single EMAIL reward (Sandbox).
 */
function send_tremendous_reward(string $email, string $name, float $amountUsd = 5.0): array
{
    $apiKey = get_tremendous_api_key();
    $url    = 'https://testflight.tremendous.com/api/v2/orders';

    $payload = [
        'payment' => [
            'funding_source_id' => 'BALANCE',
        ],
        'reward' => [
            'value' => [
                'denomination'  => $amountUsd,
                'currency_code' => 'USD',
            ],
            'delivery' => [
                'method' => 'EMAIL',
            ],
            'recipient' => [
                'name'  => $name,
                'email' => $email,
            ],
            // Optional: restrict brands. These IDs are examples from docs.
            'products' => [
                'OKMHM2X2OHYV',
                'KV934TZ93NQM',
                'ET0ZVETV5ILN',
                'Q24BD9EZ332JT',
                'TBAJH7YLFVS5',
            ],
        ],
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json',
            'Accept: application/json',
        ],
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
    ]);

    $body     = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($body === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception('cURL error while calling Tremendous: ' . $error);
    }

    curl_close($ch);

    $data = json_decode($body, true);

    if ($httpCode < 200 || $httpCode >= 300) {
        // You may want to log this in a real app
        throw new Exception('Tremendous API error (HTTP ' . $httpCode . '): ' . $body);
    }

    return $data;
}
