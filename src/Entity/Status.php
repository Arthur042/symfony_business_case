<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $advancement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdvancement(): ?string
    {
        return $this->advancement;
    }

    public function setAdvancement(string $advancement): self
    {
        $this->advancement = $advancement;

        return $this;
    }
}
