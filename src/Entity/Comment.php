<?php

namespace App\Entity;

use App\Enum\StatusCommentEnum;
use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $contributor = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Media $media = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'comments')]
    private ?self $parrentComment = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parrentComment')]
    private Collection $comments;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(enumType: StatusCommentEnum::class)]
    private ?StatusCommentEnum $status = null;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContributor(): ?User 
    {
        return $this->contributor;
    }

    public function setContributor(?User $contributor): static
    {
        $this->contributor = $contributor;

        return $this;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): static
    {
        $this->media = $media;

        return $this;
    }

    public function getParrentComment(): ?self
    {
        return $this->parrentComment;
    }

    public function setParrentComment(?self $parrentComment): static
    {
        $this->parrentComment = $parrentComment;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(self $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setParrentComment($this);
        }

        return $this;
    }

    public function removeComment(self $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getParrentComment() === $this) {
                $comment->setParrentComment(null);
            }
        }

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getStatus(): ?StatusCommentEnum
    {
        return $this->status;
    }

    public function setStatus(StatusCommentEnum $status): static
    {
        $this->status = $status;

        return $this;
    }
}
