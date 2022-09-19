<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
  normalizationContext: ['groups' => [Category::READ]],
  denormalizationContext: ['groups' => [Category::WRITE]]
)]
#[Get(
  normalizationContext: ['groups' => [Category::READ, Category::GET]],
)]
#[GetCollection(
  normalizationContext: ['groups' => [Category::READ, Category::GET_COLLECTION]],
)]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
  const READ = 'category:read';
  const WRITE = 'category:write';
  const GET = 'category:get';
  const GET_COLLECTION = 'category:get:collection';

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column()]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  #[Groups([Category::READ, Joke::READ])]
  private ?string $name = null;

  #[ORM\ManyToMany(targetEntity: Joke::class, mappedBy: 'categories')]
  #[Groups([Category::READ])]
  private Collection $jokes;

  public function __construct()
  {
    $this->jokes = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  /**
   * @return Collection<int, Joke>
   */
  public function getJokes(): Collection
  {
    return $this->jokes;
  }

  public function addJoke(Joke $joke): self
  {
    if (!$this->jokes->contains($joke)) {
      $this->jokes[] = $joke;
      $joke->addCategory($this);
    }

    return $this;
  }

  public function removeJoke(Joke $joke): self
  {
    if ($this->jokes->removeElement($joke)) {
      $joke->removeCategory($this);
    }

    return $this;
  }
}
