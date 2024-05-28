<?php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthentificationTest extends WebTestCase
{
    public function testLoginSuccess(): void
    {
       $client = static::createClient();
        $client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'user1@example.com',
            'password' => 'password'
        ]));

        $this->assertResponseIsSuccessful();
        $responseContent = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $responseContent);

    }

    public function testLoginWithInvalidCredentials(): void
{
    $client = static::createClient();
    $client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/ld+json'], json_encode([
        'email' => 'invalidemail@example.com',
        'password' => 'wrongpassword'
    ]));

    $this->assertResponseStatusCodeSame(401);
}

public function testSuccessfulRegistration(): void
{
    $client = static::createClient();
    $uniqueEmail = 'test' . rand(1000, 9999) . '@example.com';
    $client->request('POST', '/api/register', [], [], ['CONTENT_TYPE' => 'application/ld+json'], json_encode([
        'email' => $uniqueEmail,
        'password' => '12345'
    ]));

    $responseContent = json_decode($client->getResponse()->getContent(), true);
    $this->assertArrayHasKey('token', $responseContent);
}

public function testRegistrationWithExistingEmail(): void
{
    $client = static::createClient();
    $client->request('POST', '/api/register', [], [], ['CONTENT_TYPE' => 'application/ld+json'], json_encode([
        'email' => 'user1@example.com',
        'password' => '12345'
    ]));

    $this->assertResponseStatusCodeSame(409);
}

}