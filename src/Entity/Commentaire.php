<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_commentaire = null;

    #[ORM\Column]
    private ?int $likes_count = null;

    #[ORM\Column]
    private ?int $dislikes_count = null;

    #[ORM\ManyToOne(inversedBy: 'commentaire')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $user = null;

    #[ORM\ManyToOne(targetEntity: Photo::class, inversedBy: 'commentaire')]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private ?Photo $photo;

    #[ORM\OneToMany(mappedBy: 'commentaire', targetEntity: LikesCommentaire::class)]
    private $likesCommentaire;


    /*#[ORM\OneToOne(targetEntity: Commentaire::class, mappedBy: 'commentaire', cascade: ['persist', 'remove'])]
    private ?self $commentaire = null;*/


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateCommentaire(): ?\DateTimeInterface
    {
        return $this->date_commentaire;
    }

    public function setDateCommentaire(\DateTimeInterface $date_commentaire): static
    {
        $this->date_commentaire = $date_commentaire;

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

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPhoto(): ?Photo
    {
        return $this->photo;
    }

    public function setPhoto(?Photo $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    /*public function getCommentaire(): ?self
    {
        return $this->commentaire;
    }

    public function setCommentaire(?self $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }*/

    public function getLikesCommentaire()
    {
        return $this->likesCommentaire;
    }

    public function setLikesCommentaire($likesCommentaire)
    {
        $this->likesCommentaire = $likesCommentaire;

        return $this;
    }
}
