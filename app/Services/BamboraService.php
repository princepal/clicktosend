<?php

namespace App\Services;

use GuzzleHttp\Client;

class BamboraService
{
    protected $client;
    protected $merchantId;
    protected $apiPasscode;
    protected $apiUrl;

    public function __construct()
    {
        $this->merchantId = config('services.bambora.merchant_id');
        $this->apiPasscode = config('services.bambora.api_passcode');
        $this->apiUrl = config('services.bambora.api_url', 'https://api.na.bambora.com');
        $this->client = new Client([
            'base_uri' => $this->apiUrl,
            'headers' => [
                'Authorization' => 'Passcode ' . base64_encode($this->merchantId . ':' . $this->apiPasscode),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    // Create a payment profile (store card)
    public function createPaymentProfile($customer)
    {
        try {
            $response = $this->client->post('/v1/profiles', [
                'json' => [
                    'customer_code' => $customer['customer_code'],
                    'card' => [
                        'name' => $customer['name'],
                        'number' => $customer['card_number'],
                        'expiry_month' => $customer['expiry_month'],
                        'expiry_year' => $customer['expiry_year'],
                        'cvd' => $customer['cvd'],
                    ],
                    'billing' => [
                        'name' => $customer['name'],
                        'email_address' => $customer['email'],
                    ],
                ],
            ]);
            
            $result = json_decode($response->getBody(), true);
            \Log::info('Bambora createPaymentProfile response', $result);
            return $result;
            
        } catch (\Exception $e) {
            \Log::error('Bambora createPaymentProfile error: ' . $e->getMessage());
            throw $e;
        }
    }

    // Create a recurring billing profile
    public function createRecurringBilling($profileId, $amount, $schedule = 'monthly')
    {
        $apiPasscode = config('services.bambora.api_payment_passcode');
        $order_number = 'SUB-' . uniqid();

        $this->client = new Client([
            'base_uri' => $this->apiUrl,
            'headers' => [
                'Authorization' => 'Passcode ' . base64_encode($this->merchantId . ':' . $apiPasscode),
                'Content-Type' => 'application/json',
            ],
        ]);

        $data = [
            "amount" => $amount,
            "payment_method" => "payment_profile",
            "payment_profile" => [
                "customer_code" => $profileId
            ],
            "order_number" => $order_number,
            "recurrence" => "recurring" // Optional metadata
        ];

        try {
            $response = $this->client->post("/v1/payments", [
                'json' => $data
            ]);
            $result = json_decode($response->getBody(), true);
            \Log::info('Bambora createRecurringBilling response', $result);
            return $result;

        } catch (\Exception $e2) {
            \Log::error('Bambora createRecurringBilling error: ' . $e2->getMessage());
            throw $e2;
        }
    }
    public function updateCardForExistingProfile($customer_code, $cardData)
    {
        $apiPasscode = config('services.bambora.api_passcode');
        $order_number = 'SUB-' . uniqid();

        $this->client = new Client([
            'base_uri' => $this->apiUrl,
            'headers' => [
                'Authorization' => 'Passcode ' . base64_encode($this->merchantId . ':' . $apiPasscode),
                'Content-Type' => 'application/json',
            ],
        ]);

        $data = [
            'card' => $cardData
        ];

        try {
            $response = $this->client->put("v1/profiles/{$customer_code}", [
                'json' => $data
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            \Log::error("Error updating profile {$customer_code}: " . $e->getMessage());
            throw $e;
        }
    }


} 