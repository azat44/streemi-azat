<?php

namespace App\Entity;

use App\Repository\EpisodeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EpisodeRepository::class)]
class Episode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'episodes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Season $season = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $duration = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $releaseDateAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): static
    {
        $this->season = $season;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDuration(): ?\DateTimeImmutable
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeImmutable $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getReleaseDateAt(): ?\DateTimeImmutable
    {
        return $this->releaseDateAt;
    }

    public function setReleaseDateAt(\DateTimeImmutable $releaseDateAt): static
    {
        $this->releaseDateAt = $releaseDateAt;

        return $this;
    }
}
