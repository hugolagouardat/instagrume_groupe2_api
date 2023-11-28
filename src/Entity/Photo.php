<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

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

    #[ORM\ManyToOne(inversedBy: 'photo', targetEntity: User::class)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'photo', targetEntity: LikesPhoto::class)]
    #[ORM\JoinColumn(nullable: true)]
    private $likesPhoto;

    #[ORM\OneToMany(mappedBy: 'photo', targetEntity: Commentaire::class)]
    #[ORM\JoinColumn(nullable: true)]
    private $commentaire;
    

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

    public function getCommentaire()
    {
        return $this->commentaire;
    }

    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getLikesPhoto()
    {
        return $this->likesPhoto;
    }

    public function setLikesPhoto($likesPhoto)
    {
        $this->likesPhoto = $likesPhoto;

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

}
