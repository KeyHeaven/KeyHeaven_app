<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Configuration;
use App\Entity\Developers;
use App\Entity\Editor;
use App\Entity\Game;
use App\Entity\Platform;
use App\Repository\CategoryRepository;
use App\Repository\ConfigurationRepository;
use App\Repository\DevelopersRepository;
use App\Repository\EditorRepository;
use App\Repository\PlatformRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\PlatformFixtures;
use App\DataFixtures\EditorFixtures;
use App\DataFixtures\DeveloperFixtures;
use DateTime;
use App\Entity\ActivationCode;
class GameFixtures extends Fixture implements DependentFixtureInterface
{
    private  CategoryRepository $categoryRepository;
    private PlatformRepository $platformRepository;
    private EditorRepository $editorRepository;
    private ConfigurationRepository $configurationRepository;
    private DevelopersRepository $developersRepository;
    
    public function __construct(CategoryRepository $categoryRepository, PlatformRepository $platformRepository, DevelopersRepository $developersRepository, EditorRepository $editorRepository, ConfigurationRepository $configurationRepository){
        $this->categoryRepository = $categoryRepository;
        $this->platformRepository = $platformRepository;
        $this->editorRepository = $editorRepository;
        $this->configurationRepository = $configurationRepository;
        $this->developersRepository = $developersRepository;
    }

    public function getDependencies()
    {
        return [
            PlatformFixtures::class,
            EditorFixtures::class,
            DeveloperFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $jsonFile = __DIR__ . '/games.json';
        $gamesData = json_decode(file_get_contents($jsonFile), true);
        $counter = 1;
        foreach ($gamesData as $gameData) {
            $price = (float) str_replace('€', '', $gameData['price']);
            $configuration = new Configuration();
            $configuration->setProcessor($gameData['requiredSpecifications']['processor'])
                ->setGraphicsCard($gameData['requiredSpecifications']['graphics'])
                ->setRamMemory($gameData['requiredSpecifications']['memory'])
                ->setOperatingSystem($gameData['requiredSpecifications']['os'])
                ->setStorage($gameData['requiredSpecifications']['storage'])
                ->setDirectX($gameData['requiredSpecifications']['directX'])
                ->setAdditionalNotes($gameData['requiredSpecifications']['additionalNotes'] ?? '')
                ->setDisplay($gameData['requiredSpecifications']['display'] ?? '');
            $manager->persist($configuration);
            $game = new Game();
            $game->setTitle($gameData['title'])
                ->setDescription($gameData['description'])
                ->setPrice($price)
                ->setExitDate(new DateTime($gameData['releaseDate']))
                ->setImage($gameData['image'])
                ->addPlatform($this->platformRepository->findOneBy(['platformName' => $gameData['platform']]) ?? new Platform())
                ->setDeveloper($this->developersRepository->findOneBy(['name' => $gameData['developer']]) ?? new Developers())
                ->setEditor($this->editorRepository->findOneBy(['name' => $gameData['publisher']]) ?? new Editor())
                ->setConfiguration($configuration);

            if ($counter % 2 == 0) {
                $numberOfCodes = 10;

            for ($i = 0; $i < $numberOfCodes; $i++) {
            $code = $this->generatectivationCode();
            $activationCode = new ActivationCode();
            $activationCode->setName('Code #' . $counter)
                ->setCode($code)
                ->setIsAvailable(true)
                ->setGame($game);

            $manager->persist($activationCode);
            }
        }
            $manager->persist($game);
            $counter++;
        }

        $manager->flush();
    }
private function generatectivationCode(): string
{
    $segments = [];
    for ($i = 0; $i < 5; $i++) {
        $segments[] = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 5)), 0, 5);
    }

    return implode('-', $segments);
}
}
