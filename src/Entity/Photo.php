<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_poste = null;

    #[ORM\Column]
    private ?int $likes_count = null;

    #[ORM\Column]
    private ?int $dislikes_count = null;

    #[ORM\Column]
    private ?bool $is_locked = null;

    #[ORM\OneToMany(mappedBy: 'photo', targetEntity: LikesPhoto::class)]
    private Collection $likesPhotos;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'photo', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    public function __construct()
    {
        $this->likesPhotos = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

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

    public function getDatePoste(): ?\DateTimeInterface
    {
        return $this->date_poste;
    }

    public function setDatePoste(\DateTimeInterface $date_poste): static
    {
        $this->date_poste = $date_poste;

        return $this;
    }

    public function getLikesCount(): ?int
    {
        return $this->likes_count;
    }

    public function setLikesCount(int $likes_count): static
    {
        $this->likes_count = $likes_count;

        return $this;
    }

    public function getDislikesCount(): ?int
    {
        return $this->dislikes_count;
    }

    public function setDislikesCount(int $dislikes_count): static
    {
        $this->dislikes_count = $dislikes_count;

        return $this;
    }

    public function isIsLocked(): ?bool
    {
        return $this->is_locked;
    }

    public function setIsLocked(bool $is_locked): static
    {
        $this->is_locked = $is_locked;

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
            $likesPhoto->setPhoto($this);
        }

        return $this;
    }

    public function removeLikesPhoto(LikesPhoto $likesPhoto): static
    {
        if ($this->likesPhotos->removeElement($likesPhoto)) {
            // set the owning side to null (unless already changed)
            if ($likesPhoto->getPhoto() === $this) {
                $likesPhoto->setPhoto(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

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
            $commentaire->setPhoto($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPhoto() === $this) {
                $commentaire->setPhoto(null);
            }
        }

        return $this;
    }
}
