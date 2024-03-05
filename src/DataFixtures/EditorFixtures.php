<?php

namespace App\DataFixtures;

use App\Entity\Editor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EditorFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $jsonFile = __DIR__ . '/editors.json';
        $editorsData = json_decode(file_get_contents($jsonFile), true);

        foreach ($editorsData as $editorName) {
            $editor = new Editor();
            $editor->setName($editorName);
            $manager->persist($editor);
        }

        $manager->flush();
    }
}
