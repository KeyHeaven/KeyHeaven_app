<?php

namespace App\Tests\Repository;

use App\Entity\Game;
use App\Entity\Purchasing;
use App\Entity\PurchaseDetail;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\PurchaseDetailRepository;
use App\Repository\PurchasingRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RepositoryTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testGameRepository(): void
    {
        $gameRepository = $this->entityManager->getRepository(Game::class);

        $game = new Game();
        $game->setTitle('Test Game')
             ->setDescription('A test game for integration testing')
             ->setPrice(9.99)
             ->setExitDate(new \DateTime())
             ->setImage('test_game.jpg');

        $this->entityManager->persist($game);
        $this->entityManager->flush();

        $foundGame = $gameRepository->find($game->getId());

        $this->assertInstanceOf(Game::class, $foundGame);
        $this->assertSame('Test Game', $foundGame->getTitle());
    }


    public function testPurchasingRepository(): void
    {
        $purchasingRepository = $this->entityManager->getRepository(Purchasing::class);

        $purchasing = new Purchasing();
        $purchasing->setPurchaseDate(new \DateTime())
                   ->setTotalAmount(99.99)
                   ->setStatus('COMPLETED');

        $this->entityManager->persist($purchasing);
        $this->entityManager->flush();

        $foundPurchasing = $purchasingRepository->find($purchasing->getId());

        $this->assertInstanceOf(Purchasing::class, $foundPurchasing);
        $this->assertSame(99.99, $foundPurchasing->getTotalAmount());
    }

    public function testPurchaseDetailRepository(): void
    {
        $purchaseDetailRepository = $this->entityManager->getRepository(PurchaseDetail::class);

        $purchasing = new Purchasing();
        $purchasing->setPurchaseDate(new \DateTime())
                   ->setTotalAmount(99.99)
                   ->setStatus('COMPLETED');

        $this->entityManager->persist($purchasing);

        $purchaseDetail = new PurchaseDetail();
        $purchaseDetail->setQuantity(2)
                       ->setUnitPrice(49.99)
                       ->setPurchase($purchasing);

        $this->entityManager->persist($purchaseDetail);
        $this->entityManager->flush();

        $foundPurchaseDetails = $purchaseDetailRepository->findByPurchaseId($purchasing->getId());

        $this->assertCount(1, $foundPurchaseDetails);
        $this->assertInstanceOf(PurchaseDetail::class, $foundPurchaseDetails[0]);
        $this->assertSame(2, $foundPurchaseDetails[0]->getQuantity());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}