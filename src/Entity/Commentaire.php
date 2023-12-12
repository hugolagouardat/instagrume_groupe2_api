<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

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

    #[ORM\ManyToOne(targetEntity: Commentaire::class, inversedBy: 'commentairesEnfants')]
    #[ORM\JoinColumn(name: 'commentaire_parent_id', referencedColumnName: 'id', onDelete: "CASCADE", nullable: true)]
    private ?Commentaire $commentaireParent;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Photo $photo = null;

    #[ORM\OneToMany(mappedBy: 'commentaire', targetEntity: LikesCommentaire::class)]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private Collection $likes_commentaire;

    public function __construct()
    {
        $this->likes_commentaire = new ArrayCollection();
    }


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

    public function getDateCommentaire(): ?string
    {
        return $this->date_commentaire ? $this->date_commentaire->format('d/m/Y H:i:s') : null;
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

   
    public function getCommentaireParent(): ?self
    {
        return $this->commentaireParent;
    }

    public function setCommentaireParent(?self $commentaire): static
    {
        $this->commentaireParent = $commentaire;

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

    public function getPhoto(): ?Photo
    {
        return $this->photo;
    }

    public function setPhoto(?Photo $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, LikesCommentaire>
     */
    public function getLikesCommentaire(): Collection
    {
        return $this->likes_commentaire;
    }

    public function addLikesCommentaire(LikesCommentaire $likesCommentaire): static
    {
        if (!$this->likes_commentaire->contains($likesCommentaire)) {
            $this->likes_commentaire->add($likesCommentaire);
            $likesCommentaire->setCommentaire($this);
        }

        return $this;
    }

    public function removeLikesCommentaire(LikesCommentaire $likesCommentaire): static
    {
        if ($this->likes_commentaire->removeElement($likesCommentaire)) {
            // set the owning side to null (unless already changed)
            if ($likesCommentaire->getCommentaire() === $this) {
                $likesCommentaire->setCommentaire(null);
            }
        }

        return $this;
    }

   
}
