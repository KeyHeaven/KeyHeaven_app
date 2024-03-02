<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PaymentControllerTest extends WebTestCase
{
    public function testCreatePaymentIntent(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'user1@example.com', 
            'password' => 'password'
        ]));

        $this->assertResponseIsSuccessful();
        $responseContent = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $responseContent);
        $jwtToken = $responseContent['token'];

        $client->request('POST', '/api/create-payment-intent', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $jwtToken,
            'CONTENT_TYPE' => 'application/json'
        ], json_encode(['items' => [['id' => 1], ['id' => 2]]]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('clientSecret', $responseData);
    }
}
