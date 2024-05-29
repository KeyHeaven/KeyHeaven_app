<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use App\Entity\Developers;
use App\Entity\Editor;
use App\Entity\Game;
use App\Entity\Platform;
use App\Entity\Purchasing;
use App\Entity\Review;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
    public function testCategoryEntity(): void
    {
        $category = new Category();
        $category->setLabel('Action');

        $this->assertSame('Action', $category->getLabel());
    }

    public function testDevelopersEntity(): void
    {
        $developer = new Developers();
        $developer->setName('Rockstar Games');

        $this->assertSame('Rockstar Games', $developer->getName());
    }

    public function testEditorEntity(): void
    {
        $editor = new Editor();
        $editor->setName('Square Enix');

        $this->assertSame('Square Enix', $editor->getName());
    }

    public function testPlatformEntity(): void
    {
        $platform = new Platform();
        $platform->setPlatformName('PlayStation 5');

        $this->assertSame('PlayStation 5', $platform->getPlatformName());
    }

    public function testUserEntity(): void
    {
        $user = new User();
        $user->setEmail('test@example.com')
             ->setPassword('password')
             ->setLastname('Doe')
             ->setFirstname('John')
             ->setStripeId('stripe_id_123')
             ->setRegistrationDate(new \DateTime())
             ->setLastConnection(new \DateTime());

        $this->assertSame('test@example.com', $user->getEmail());
        $this->assertSame('password', $user->getPassword());
        $this->assertSame('Doe', $user->getLastname());
        $this->assertSame('John', $user->getFirstname());
        $this->assertSame('stripe_id_123', $user->getStripeId());
        $this->assertInstanceOf(\DateTime::class, $user->getRegistrationDate());
        $this->assertInstanceOf(\DateTime::class, $user->getLastConnection());
    }

    public function testGameEntity(): void
    {
        $game = new Game();
        $game->setTitle('Grand Theft Auto V')
             ->setDescription('An action-adventure game.')
             ->setPrice(59.99)
             ->setExitDate(new \DateTime())
             ->setImage('gta_v.jpg');

        $this->assertSame('Grand Theft Auto V', $game->getTitle());
        $this->assertSame('An action-adventure game.', $game->getDescription());
        $this->assertSame(59.99, $game->getPrice());
        $this->assertInstanceOf(\DateTime::class, $game->getExitDate());
        $this->assertSame('gta_v.jpg', $game->getImage());
    }

    public function testReviewEntity(): void
    {
        $review = new Review();
        $review->setNote(5)
               ->setComment('Great game!')
               ->setSubmitAt(new \DateTimeImmutable());

        $this->assertSame(5, $review->getNote());
        $this->assertSame('Great game!', $review->getComment());
        $this->assertInstanceOf(\DateTimeImmutable::class, $review->getSubmitAt());
    }

    public function testPurchasingEntity(): void
    {
        $purchasing = new Purchasing();
        $purchasing->setPurchaseDate(new \DateTime())
                   ->setTotalAmount(99.99)
                   ->setStatus('COMPLETED');

        $this->assertInstanceOf(\DateTime::class, $purchasing->getPurchaseDate());
        $this->assertSame(99.99, $purchasing->getTotalAmount());
        $this->assertSame('COMPLETED', $purchasing->getStatus());
    }
}