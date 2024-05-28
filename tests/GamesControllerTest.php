<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\AuthentificationTest;
use App\Entity\Game;
use App\Entity\ActivationCode;
use App\Repository\GameRepository;

class GamesControllerTest extends WebTestCase
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

    public function testGetAllGames(): void
    {

        $this->client->request('GET', '/api/games', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
            'CONTENT_TYPE' => 'application/ld+json'
        ]);

        $this->assertResponseIsSuccessful();

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData);
        $this->assertNotEmpty($responseData);

        $this->assertArrayHasKey('hydra:totalItems', $responseData);
        
        $this->assertGreaterThan(0, $responseData['hydra:totalItems']);
    }

    public function testGetGamesNotAuthenticated(): void
    {

        $this->client->request('GET', '/api/games');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }
    public function testIsInStock(): void
    {
        $game = new Game();
        
        $availableCode = new ActivationCode();
        $availableCode->setIsAvailable(true);
        $game->addActivationCode($availableCode);

        $unavailableCode = new ActivationCode();
        $unavailableCode->setIsAvailable(false);
        $game->addActivationCode($unavailableCode);

        $this->assertTrue($game->isInStock());

        $game->removeActivationCode($availableCode);
        $this->assertFalse($game->isInStock());
    }

        public function testGetGameById(): void
    {

        $gameRepository = static::getContainer()->get(GameRepository::class);
        $game = $gameRepository->findOneBy([]);

        $this->client->request('GET', '/api/games/'.$game->getId(), [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
            'CONTENT_TYPE' => 'application/ld+json'
        ]);

        $this->assertResponseIsSuccessful();

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame($game->getTitle(), $responseData['title']);
        $this->assertSame($game->getDescription(), $responseData['description']);
        $this->assertSame($game->getPrice(), $responseData['price']);
    }

    public function testGetNonExistingGame(): void
    {

        $this->client->request('GET', '/api/games/9999', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
            'CONTENT_TYPE' => 'application/ld+json'
        ]); 

        $this->assertResponseStatusCodeSame(404);
    }

    public function testGetGamesSortedByReleaseDate(): void
    {

        $this->client->request('GET', '/api/games?order[exitDate]=desc', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
            'CONTENT_TYPE' => 'application/ld+json'
        ]);

        $this->assertResponseIsSuccessful();

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertArrayHasKey('hydra:totalItems', $responseData);
        
        $this->assertGreaterThan(0, $responseData['hydra:totalItems']);

        $games = $responseData['hydra:member'];
        $this->assertIsArray($games);

        $lastExitDate = null;
        foreach ($games as $gameData) {
            $currentExitDate = new \DateTime($gameData['exitDate']);
            if ($lastExitDate !== null) {
                $this->assertLessThanOrEqual($lastExitDate, $currentExitDate);
            }
            $lastExitDate = $currentExitDate;
        }
    }
}
