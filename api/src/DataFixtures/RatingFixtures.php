<?php

namespace App\DataFixtures;

use App\Entity\Joke;
use App\Entity\Rating;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class RatingFixtures extends Fixture implements DependentFixtureInterface
{
  public function load(ObjectManager $manager): void
  {
    $faker = Faker\Factory::create('fr_FR');

    $jokes = $manager->getRepository(Joke::class)->findAll();

    for ($i = 0; $i < 100; $i++) {
      $rating = new Rating();

      $rating
        ->setStar($faker->numberBetween(1, 5))
        ->setJoke($faker->randomElement($jokes));

      $manager->persist($rating);
    }

    $manager->flush();
  }

  public function getDependencies(): array
  {
    return [
      JokeFixtures::class
    ];
  }
}
