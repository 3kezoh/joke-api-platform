<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
  normalizationContext: ['groups' => [User::READ]],
  denormalizationContext: ['groups' => [User::WRITE]],
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
  const READ = 'user:read';
  const WRITE = 'user:write';

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column()]
  private ?int $id = null;

  #[ORM\Column(length: 180, unique: true)]
  #[Groups([User::READ, User::WRITE, Joke::READ])]
  private ?string $email = null;

  #[ORM\Column]
  private array $roles = [];

  /**
   * @var string The hashed password
   */
  #[ORM\Column]
  #[Groups([User::WRITE])]
  private ?string $password = null;

  #[ORM\OneToMany(mappedBy: 'author', targetEntity: Joke::class)]
  private Collection $jokes;

  public function __construct()
  {
    $this->jokes = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): self
  {
    $this->email = $email;

    return $this;
  }

  /**
   * A visual identifier that represents this user.
   *
   * @see UserInterface
   */
  public function getUserIdentifier(): string
  {
    return (string) $this->email;
  }

  /**
   * @see UserInterface
   */
  public function getRoles(): array
  {
    $roles = $this->roles;
    // guarantee every user at least has ROLE_USER
    $roles[] = 'ROLE_USER';

    return array_unique($roles);
  }

  public function setRoles(array $roles): self
  {
    $this->roles = $roles;

    return $this;
  }

  /**
   * @see PasswordAuthenticatedUserInterface
   */
  public function getPassword(): string
  {
    return $this->password;
  }

  public function setPassword(string $password): self
  {
    $this->password = $password;

    return $this;
  }

  /**
   * @see UserInterface
   */
  public function eraseCredentials()
  {
    // If you store any temporary, sensitive data on the user, clear it here
    // $this->plainPassword = null;
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
      $joke->setAuthor($this);
    }

    return $this;
  }

  public function removeJoke(Joke $joke): self
  {
    if ($this->jokes->removeElement($joke)) {
      // set the owning side to null (unless already changed)
      if ($joke->getAuthor() === $this) {
        $joke->setAuthor(null);
      }
    }

    return $this;
  }
}
