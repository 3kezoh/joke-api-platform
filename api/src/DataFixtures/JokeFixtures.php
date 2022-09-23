<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Joke;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class JokeFixtures extends Fixture implements DependentFixtureInterface
{
  public function load(ObjectManager $manager): void
  {
    $faker = Faker\Factory::create('fr_FR');

    $categories = $manager->getRepository(Category::class)->findAll();
    $user = $this->getReference(UserFixtures::USER);

    for ($i = 0; $i < 50; $i++) {
      $joke = new Joke();

      $joke
        ->setText($faker->sentence())
        ->setAuthor($user);

      for ($j = 0; $j < 3; $j++) {
        $joke->addCategory($faker->randomElement($categories));
      }

      $manager->persist($joke);
    }

    $manager->flush();
  }

  public function getDependencies(): array
  {
    return [
      CategoryFixtures::class,
      UserFixtures::class
    ];
  }
}
