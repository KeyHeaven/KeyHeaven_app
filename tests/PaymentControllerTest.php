<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\AuthentificationTest;

class PaymentControllerTest extends WebTestCase
{

    private $client;
    private $token;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        
        $this->client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'user1@example.com',
            'password' => 'password'
        ]));

        $this->assertResponseIsSuccessful();
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->token = $responseContent['token'];
    }
    
    public function testCreatePaymentIntent(): void
    {

        $this->client->request('POST', '/api/create-payment-intent', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
            'CONTENT_TYPE' => 'application/json'
        ], json_encode(['items' => [['id' => 1], ['id' => 2]]]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('clientSecret', $responseData);
    }
}
