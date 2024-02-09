<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserInformation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordEncoder;
    public function __construct(UserPasswordHasherInterface  $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail('user' . $i . '@example.com')
                ->setRoles(['ROLE_USER'])
                ->setPassword($this->passwordEncoder->hashPassword($user, 'password'))
                ->setLastname('LastName' . $i)
                ->setFirstname('FirstName' . $i)
                ->setRegistrationDate(new \DateTime())
                ->setLastConnection(new \DateTime());

            $userInformation = new UserInformation();
            $userInformation->setUser($user)
                ->setAdress('Addresse de l\'utilisateur '. $i)
                ->setDepartment(62000)
                ->setCity("Arras");

            $manager->persist($user);
            $manager->persist($userInformation);
        }

        $manager->flush();
    }
}