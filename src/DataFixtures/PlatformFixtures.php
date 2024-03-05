<?php

namespace App\DataFixtures;

use App\Entity\Platform;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PlatformFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $jsonFile = __DIR__ . '/platforms.json';
        $platformsData = json_decode(file_get_contents($jsonFile), true);

        foreach ($platformsData as $platformName) {
            $platform = new Platform();
            $platform->setPlatformName($platformName);
            $manager->persist($platform);
        }

        $manager->flush();
    }
}
