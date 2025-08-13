<?php

require_once 'vendor/autoload.php';

// Load Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use GuzzleHttp\Client;

echo "=== Bambora API Test ===\n";

// Get configuration
$merchantId = config('services.bambora.merchant_id');
$apiPasscode = config('services.bambora.api_passcode');
$apiUrl = config('services.bambora.api_url');

echo "Merchant ID: " . $merchantId . "\n";
echo "API Passcode: " . substr($apiPasscode, 0, 8) . "..." . "\n";
echo "API URL: " . $apiUrl . "\n";

// Test 1: Passcode Authentication
echo "\n=== Test 1: Passcode Authentication ===\n";
$client1 = new Client([
    'base_uri' => $apiUrl,
    'headers' => [
        'Authorization' => 'Passcode ' . $apiPasscode,
        'Content-Type' => 'application/json',
    ],
]);

try {
    $response = $client1->post('/v1/profiles', [
        'json' => [
            'customer_code' => 'test_customer_' . time(),
            'card' => [
                'name' => 'Test User',
                'number' => '4030000010001234',
                'expiry_month' => '12',
                'expiry_year' => '2025',
                'cvd' => '123',
            ],
            'billing' => [
                'name' => 'Test User',
                'email_address' => 'test@example.com',
            ],
        ],
    ]);
    echo "Passcode Auth - Status: " . $response->getStatusCode() . "\n";
    echo "Response: " . $response->getBody() . "\n";
} catch (Exception $e) {
    echo "Passcode Auth Error: " . $e->getMessage() . "\n";
    if ($e->hasResponse()) {
        $response = $e->getResponse();
        echo "Response Body: " . $response->getBody() . "\n";
    }
}

// Test 2: Basic Authentication
echo "\n=== Test 2: Basic Authentication ===\n";
$client2 = new Client([
    'base_uri' => $apiUrl,
    'headers' => [
        'Authorization' => 'Basic ' . base64_encode($merchantId . ':' . $apiPasscode),
        'Content-Type' => 'application/json',
    ],
]);

try {
    $response = $client2->post('/v1/profiles', [
        'json' => [
            'customer_code' => 'test_customer_' . time(),
            'card' => [
                'name' => 'Test User',
                'number' => '4030000010001234',
                'expiry_month' => '12',
                'expiry_year' => '2025',
                'cvd' => '123',
            ],
            'billing' => [
                'name' => 'Test User',
                'email_address' => 'test@example.com',
            ],
        ],
    ]);
    echo "Basic Auth - Status: " . $response->getStatusCode() . "\n";
    echo "Response: " . $response->getBody() . "\n";
} catch (Exception $e) {
    echo "Basic Auth Error: " . $e->getMessage() . "\n";
    if ($e->hasResponse()) {
        $response = $e->getResponse();
        echo "Response Body: " . $response->getBody() . "\n";
    }
}

// Test 3: Try different endpoints
echo "\n=== Test 3: Alternative Endpoints ===\n";
$endpoints = ['/profiles', '/v1/profiles', '/api/profiles', '/payment/profiles'];

foreach ($endpoints as $endpoint) {
    echo "Testing endpoint: " . $endpoint . "\n";
    try {
        $response = $client1->get($endpoint);
        echo "GET " . $endpoint . " - Status: " . $response->getStatusCode() . "\n";
    } catch (Exception $e) {
        echo "GET " . $endpoint . " - Error: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Test Complete ===\n"; 