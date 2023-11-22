<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use OpenApi\Attributes as OA;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    #[OA\Property(type:"array", items: new OA\Items(type:"string"))]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $avatar = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $ban = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: LikesPhoto::class)]
    private Collection $likesPhotos;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Photo::class)]
    private Collection $photos;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: LikesCommentaire::class)]
    private Collection $likesCommentaires;

    public function __construct()
    {
        $this->likesPhotos = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->likesCommentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
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

    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isBan(): ?bool
    {
        return $this->ban;
    }

    public function setBan(bool $ban): static
    {
        $this->ban = $ban;

        return $this;
    }

    /**
     * @return Collection<int, LikesPhoto>
     */
    public function getLikesPhotos(): Collection
    {
        return $this->likesPhotos;
    }

    public function addLikesPhoto(LikesPhoto $likesPhoto): static
    {
        if (!$this->likesPhotos->contains($likesPhoto)) {
            $this->likesPhotos->add($likesPhoto);
            $likesPhoto->setUser($this);
        }

        return $this;
    }

    public function removeLikesPhoto(LikesPhoto $likesPhoto): static
    {
        if ($this->likesPhotos->removeElement($likesPhoto)) {
            // set the owning side to null (unless already changed)
            if ($likesPhoto->getUser() === $this) {
                $likesPhoto->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setUser($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getUser() === $this) {
                $photo->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LikesCommentaire>
     */
    public function getLikesCommentaires(): Collection
    {
        return $this->likesCommentaires;
    }

    public function addLikesCommentaire(LikesCommentaire $likesCommentaire): static
    {
        if (!$this->likesCommentaires->contains($likesCommentaire)) {
            $this->likesCommentaires->add($likesCommentaire);
            $likesCommentaire->setUser($this);
        }

        return $this;
    }

    public function removeLikesCommentaire(LikesCommentaire $likesCommentaire): static
    {
        if ($this->likesCommentaires->removeElement($likesCommentaire)) {
            // set the owning side to null (unless already changed)
            if ($likesCommentaire->getUser() === $this) {
                $likesCommentaire->setUser(null);
            }
        }

        return $this;
    }
}
