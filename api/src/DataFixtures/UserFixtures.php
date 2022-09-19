<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    $password = '$2y$13$U6y78JDKc5hKVI1atClBh.IgirRAVAdWkhpOxmATJsY3qweg864me';

    $user = new User();

    $user
      ->setEmail('user@localhost')
      ->setRoles(["ROLE_USER"])
      ->setPassword($password);

    $manager->persist($user);

    $admin = new User();

    $admin
      ->setEmail('admin@localhost')
      ->setRoles(["ROLE_ADMIN"])
      ->setPassword($password);

    $manager->persist($admin);

    $moderator = new User();

    $moderator
      ->setEmail('moderator@localhost')
      ->setRoles(["ROLE_MODERATOR"])
      ->setPassword($password);

    $manager->persist($moderator);

    $manager->flush();
  }
}
