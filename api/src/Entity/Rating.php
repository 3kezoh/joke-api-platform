<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: RatingRepository::class)]
class Rating
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column()]
  private ?int $id = null;

  #[ORM\Column]
  private ?int $star = null;

  #[ORM\ManyToOne(inversedBy: 'ratings')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Joke $joke = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getStar(): ?int
  {
    return $this->star;
  }

  public function setStar(int $star): self
  {
    $this->star = $star;

    return $this;
  }

  public function getJoke(): ?Joke
  {
    return $this->joke;
  }

  public function setJoke(?Joke $joke): self
  {
    $this->joke = $joke;

    return $this;
  }
}
