<?php

namespace App\Tests;

use App\DataFixtures\DeveloperFixtures;
use App\DataFixtures\EditorFixtures;
use App\DataFixtures\GameFixtures;
use App\DataFixtures\PlatformFixtures;
use App\Entity\Developers;
use App\Entity\Editor;
use App\Entity\Game;
use App\Entity\Platform;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Configuration;
use App\Entity\Category;

class DataFixturesTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
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

    public function testGameFixtures(): void
    {
        $developerFixture = new DeveloperFixtures();
        $developerFixture->load($this->entityManager);

        $editorFixture = new EditorFixtures();
        $editorFixture->load($this->entityManager);

        $platformFixture = new PlatformFixtures();
        $platformFixture->load($this->entityManager);

        // Retrieve repositories for dependencies
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $platformRepository = $this->entityManager->getRepository(Platform::class);
        $developersRepository = $this->entityManager->getRepository(Developers::class);
        $editorRepository = $this->entityManager->getRepository(Editor::class);
        $configurationRepository = $this->entityManager->getRepository(Configuration::class);

        // Pass all necessary arguments to the GameFixtures constructor
        $fixture = new GameFixtures(
            $categoryRepository,
            $platformRepository,
            $developersRepository,
            $editorRepository,
            $configurationRepository
        );
        $fixture->load($this->entityManager);

        $gameRepository = $this->entityManager->getRepository(Game::class);
        $games = $gameRepository->findAll();

        $this->assertGreaterThan(0, count($games));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Fermez la connexion à la base de données après chaque test
        $this->entityManager->close();
        // Comment out the following line
        // $this->entityManager = null;
    }
}
