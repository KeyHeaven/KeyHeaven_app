<?php

namespace App\DataFixtures;

use App\Entity\Game;
use App\Entity\Purchasing;
use App\Entity\PurchaseDetail;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\ActivationCode;

class PurchaseFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $users = $manager->getRepository(User::class)->findAll();
        $games = $manager->getRepository(Game::class)->findAll();

        foreach ($users as $user) {
            $numPurchases = rand(1, 10);

            for ($i = 0; $i < $numPurchases; $i++) {
                $purchase = new Purchasing();
                $purchase->setPurchaseDate(new \DateTime('-'.rand(1, 365).' days'));
                $purchase->setUser($user);

                $numDetails = rand(1, 5);
                $totalAmount = 0;

                for ($j = 0; $j < $numDetails; $j++) {
                    $purchaseDetail = new PurchaseDetail();
                    $purchaseDetail->setQuantity(rand(1, 3));

                    $game = $games[array_rand($games)];
                    $purchaseDetail->setGame($game);
                    $purchaseDetail->setUnitPrice($game->getPrice());
                    $purchaseDetail->setPurchase($purchase);
                    $totalAmount += $purchaseDetail->getQuantity() * $purchaseDetail->getUnitPrice();

                     $code = $this->generatectivationCode();
                    $activationCode = new ActivationCode();
                    $activationCode->setCode($code);
                    $activationCode->setGame($game);
                    $activationCode->setIsAvailable(false);
                    $activationCode->setName('CODE_'.$game->getTitle());

                    $purchaseDetail->setActivationCode($activationCode);
                    
                    $manager->persist($activationCode);
                    
                    $manager->persist($purchaseDetail);
                }

                $purchase->setTotalAmount($totalAmount);
                $purchase->setStatus('COMPLETED');

                $manager->persist($purchase);
            }
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

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            GameFixtures::class,
        ];
    }
}