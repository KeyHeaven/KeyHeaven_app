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

class GameFixtures extends Fixture
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
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <=10; $i++ ){
            $category = new Category();
            $category->setLabel('category ' .$i);

            $platform = new Platform();
            $platform->setPlatformName('Platform name '.$i);

            $editor = new Editor();
            $editor->setName('Editor ' .$i);

            $developer = new Developers();
            $developer->setName('Developer '.$i);

            $manager->persist($category);
            $manager->persist($platform);
            $manager->persist($editor);
            $manager->persist($developer);
        }

        $configuration = new Configuration();
        $configuration->setOperatingSystem("Windows 10/11 with update - 64 bits")
            ->setProcessor('Intel Core 2 or AMD athlon 64X2')
            ->setRamMemory('4 GB RAM')
            ->setGraphicsCard("Intel GMA X4500, NVIDIA GeForce 9600M GT, AMD/ATI Mobility Radeon HD 3650 - requires 256MB VRAM and DirectX 11")
            ->setDirectX('Version 11')
            ->setStorage('7 GB available space')
            ->setAdditionalNotes('Performance scales with higher-end systems.')
            ->setDisplay('1024x768');

        $manager->persist($configuration);
        $manager->flush();

        for($i =1; $i <= 10; $i++){
            $game = new Game();

            $game->setTitle('Jeu '.$i)
                ->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean vehicula orci nec justo condimentum bibendum. Sed sodales ac neque vel malesuada. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Aliquam mattis tincidunt ultricies. Aliquam metus risus, sodales nec tortor sit amet, eleifend rutrum urna. Pellentesque non massa felis. Nam sodales velit eu consectetur porta. Proin faucibus diam non lobortis lobortis. In in aliquam risus.
                    Proin sapien diam, tincidunt ut ipsum ullamcorper, viverra pharetra lectus. Cras tristique sit amet ipsum vel tempus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Fusce sit amet interdum ante. Mauris sodales est eget eros facilisis luctus. Donec venenatis tempor arcu, eget bibendum odio dictum vitae. Pellentesque in faucibus augue. Donec in rutrum augue, ultrices lobortis erat.")
                ->setPrice(22.55)
                ->setExitDate(new \DateTime())
                ->setImage('image'.$i.'.png')
                ->addCategory($this->categoryRepository->findOneBy(['id' => 2]))
                ->addPlatform($this->platformRepository->findOneBy(['id' => 3]))
                ->setDeveloper($this->developersRepository->findOneBy(['id' => 1]))
                ->setConfiguration($this->configurationRepository->findOneBy(['id' => 1]))
                ->setEditor($this->editorRepository->findOneBy(['id' => 5]));
            $manager->persist($game);
        }
        $manager->flush();
    }

}