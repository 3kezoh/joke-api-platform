<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column()]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  private ?string $message = null;

  #[ORM\ManyToOne(inversedBy: 'comments')]
  private ?Joke $joke = null;

  #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'answers')]
  private ?self $comment = null;

  #[ORM\OneToMany(mappedBy: 'comment', targetEntity: self::class)]
  private Collection $answers;

  public function __construct()
  {
    $this->answers = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getMessage(): ?string
  {
    return $this->message;
  }

  public function setMessage(string $message): self
  {
    $this->message = $message;

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

  public function getComment(): ?self
  {
    return $this->comment;
  }

  public function setComment(?self $comment): self
  {
    $this->comment = $comment;

    return $this;
  }

  /**
   * @return Collection<int, self>
   */
  public function getAnswers(): Collection
  {
    return $this->answers;
  }

  public function addAnswer(self $answer): self
  {
    if (!$this->answers->contains($answer)) {
      $this->answers[] = $answer;
      $answer->setComment($this);
    }

    return $this;
  }

  public function removeAnswer(self $answer): self
  {
    if ($this->answers->removeElement($answer)) {
      // set the owning side to null (unless already changed)
      if ($answer->getComment() === $this) {
        $answer->setComment(null);
      }
    }

    return $this;
  }
}
