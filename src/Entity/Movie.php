<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\MediaTypeEnum;
use App\Repository\MovieRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

#[Entity(repositoryClass: MovieRepository::class)]
class Movie extends Media
{
    #[ORM\Column]
    private ?int $duration = null;

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getType(): string
    {
        return 'movie';
    }
}
