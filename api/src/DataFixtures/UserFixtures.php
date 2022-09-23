<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
  const USER = 'user';

  public function load(ObjectManager $manager): void
  {
    $password = '$2y$13$U6y78JDKc5hKVI1atClBh.IgirRAVAdWkhpOxmATJsY3qweg864me';

    $user = new User();

    $user
      ->setEmail('user@localhost')
      ->setRoles(["ROLE_USER"])
      ->setPassword($password);

    $this->addReference(self::USER, $user);

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
