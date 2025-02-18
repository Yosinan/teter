<?php

namespace App\Services;

use GuzzleHttp\Client;

class ResendEmailService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('RESEND_API_KEY');
    }

    public function sendEmail($to, $subject, $body)
    {
        $response = $this->client->post('https://api.resend.io/v1/email', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'to' => $to,
                'from' => 'example@example.com',
                'subject' => $subject,
                'html' => $body,
            ],
        ]);

        return $response;
    }
}