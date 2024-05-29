<?php

namespace App\Tests\DataFixtures;

use App\DataFixtures\DeveloperFixtures;
use App\DataFixtures\EditorFixtures;
use App\DataFixtures\GameFixtures;
use App\DataFixtures\PlatformFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Developers;
use App\Entity\Editor;
use App\Entity\Game;
use App\Entity\Platform;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DataFixturesTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->clearTables(); // Clear the tables before each test
    }

    private function clearTables(): void
    {
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();

        // Disable foreign key checks for MySQL
        if ($platform->getName() === 'mysql') {
            $connection->executeQuery('SET FOREIGN_KEY_CHECKS=0');
        }

        $connection->executeStatement('DELETE FROM user_information');
        $connection->executeStatement('DELETE FROM user');
        $connection->executeStatement('DELETE FROM game');
        $connection->executeStatement('DELETE FROM platform');
        $connection->executeStatement('DELETE FROM editor');
        $connection->executeStatement('DELETE FROM developers');

        // Re-enable foreign key checks for MySQL
        if ($platform->getName() === 'mysql') {
            $connection->executeQuery('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function testDeveloperFixtures(): void
    {
        $fixture = new DeveloperFixtures();
        $fixture->load($this->entityManager);

        $developerRepository = $this->entityManager->getRepository(Developers::class);
        $developers = $developerRepository->findAll();

        $this->assertGreaterThan(0, count($developers));
    }

    public function testEditorFixtures(): void
    {
        $fixture = new EditorFixtures();
        $fixture->load($this->entityManager);

        $editorRepository = $this->entityManager->getRepository(Editor::class);
        $editors = $editorRepository->findAll();

        $this->assertGreaterThan(0, count($editors));
    }

    public function testPlatformFixtures(): void
    {
        $fixture = new PlatformFixtures();
        $fixture->load($this->entityManager);

        $platformRepository = $this->entityManager->getRepository(Platform::class);
        $platforms = $platformRepository->findAll();

        $this->assertGreaterThan(0, count($platforms));
    }

    // public function testUserFixtures(): void
    // {
    //     $passwordHasher = $this->getMockBuilder(UserPasswordHasherInterface::class)
    //                            ->disableOriginalConstructor()
    //                            ->getMock();

    //     $passwordHasher->method('hashPassword')
    //                    ->willReturn('hashed_password');

    //     $fixture = new UserFixtures($passwordHasher);
    //     $fixture->load($this->entityManager);

    //     $userRepository = $this->entityManager->getRepository(User::class);
    //     $users = $userRepository->findAll();

    //     $this->assertGreaterThan(0, count($users));
    // }

    // public function testGameFixtures(): void
    // {
    //     $developerFixture = new DeveloperFixtures();
    //     $developerFixture->load($this->entityManager);

    //     $editorFixture = new EditorFixtures();
    //     $editorFixture->load($this->entityManager);

    //     $platformFixture = new PlatformFixtures();
    //     $platformFixture->load($this->entityManager);

    //     // Create mocks or real instances for GameFixtures dependencies
    //     $someService = $this->getMockBuilder(SomeService::class)->disableOriginalConstructor()->getMock();
    //     $anotherService = $this->getMockBuilder(AnotherService::class)->disableOriginalConstructor()->getMock();

    //     // Pass all necessary arguments to the GameFixtures constructor
    //     $fixture = new GameFixtures($someService, $anotherService, ...);
    //     $fixture->load($this->entityManager);

    //     $gameRepository = $this->entityManager->getRepository(Game::class);
    //     $games = $gameRepository->findAll();

    //     $this->assertGreaterThan(0, count($games));
    // }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Fermez la connexion à la base de données après chaque test
        $this->entityManager->close();
        // Comment out the following line
        // $this->entityManager = null;
    }
}
