<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\JokeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
  normalizationContext: ['groups' => [Joke::READ]],
  denormalizationContext: ['groups' => [Joke::WRITE]],
)]
#[Get]
#[GetCollection]
#[Post]
#[Patch]
#[Delete]
#[ORM\Entity(repositoryClass: JokeRepository::class)]
class Joke
{
  const READ = 'joke:read';
  const WRITE = 'joke:write';

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column()]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  #[Groups([Joke::READ, Joke::WRITE])]
  private ?string $text = null;

  #[ORM\OneToMany(mappedBy: 'joke', targetEntity: Rating::class, orphanRemoval: true)]
  private Collection $ratings;

  #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'jokes')]
  #[Groups([Joke::READ])]
  private Collection $categories;

  #[ORM\OneToMany(mappedBy: 'joke', targetEntity: Comment::class)]
  private Collection $comments;

  public function __construct()
  {
    $this->ratings = new ArrayCollection();
    $this->categories = new ArrayCollection();
    $this->comments = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getText(): ?string
  {
    return $this->text;
  }

  public function setText(string $text): self
  {
    $this->text = $text;

    return $this;
  }

  /**
   * @return Collection<int, Rating>
   */
  public function getRatings(): Collection
  {
    return $this->ratings;
  }

  public function addRating(Rating $rating): self
  {
    if (!$this->ratings->contains($rating)) {
      $this->ratings[] = $rating;
      $rating->setJoke($this);
    }

    return $this;
  }

  public function removeRating(Rating $rating): self
  {
    if ($this->ratings->removeElement($rating)) {
      // set the owning side to null (unless already changed)
      if ($rating->getJoke() === $this) {
        $rating->setJoke(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection<int, Category>
   */
  public function getCategories(): Collection
  {
    return $this->categories;
  }

  public function addCategory(Category $category): self
  {
    if (!$this->categories->contains($category)) {
      $this->categories[] = $category;
    }

    return $this;
  }

  public function removeCategory(Category $category): self
  {
    $this->categories->removeElement($category);

    return $this;
  }

  /**
   * @return Collection<int, Comment>
   */
  public function getComments(): Collection
  {
    return $this->comments;
  }

  public function addComment(Comment $comment): self
  {
    if (!$this->comments->contains($comment)) {
      $this->comments[] = $comment;
      $comment->setJoke($this);
    }

    return $this;
  }

  public function removeComment(Comment $comment): self
  {
    if ($this->comments->removeElement($comment)) {
      // set the owning side to null (unless already changed)
      if ($comment->getJoke() === $this) {
        $comment->setJoke(null);
      }
    }

    return $this;
  }
}
