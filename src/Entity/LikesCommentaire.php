<?php

namespace App\Entity;

use App\Repository\LikesCommentaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikesCommentaireRepository::class)]
class LikesCommentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $like_type = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'likescommentaire')]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private ?User $user;

    #[ORM\ManyToOne(targetEntity: Commentaire::class, inversedBy: 'likescommentaires')]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private ?Commentaire $commentaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isLikeType(): ?bool
    {
        return $this->like_type;
    }

    public function setLikeType(?bool $like_type): static
    {
        $this->like_type = $like_type;

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

    public function getCommentaire(): ?Commentaire
    {
        return $this->commentaire;
    }

    public function setCommentaire(?Commentaire $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }
}
