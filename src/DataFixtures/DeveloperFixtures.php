<?php

namespace App\DataFixtures;

use App\Entity\Developers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DeveloperFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $jsonFile = __DIR__ . '/developers.json';
        $developersData = json_decode(file_get_contents($jsonFile), true);

        foreach ($developersData as $developerName) {
            $developer = new Developers();
            $developer->setName($developerName);
            $manager->persist($developer);
        }

        $manager->flush();
    }
}
