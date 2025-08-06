<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;

#[ApiResource(
    operations: [new Get(), new GetCollection(), new Post()],
    normalizationContext: ['groups' => ['cuve:read']],
    denormalizationContext: ['groups' => ['cuve:write']]
)]
#[ORM\Entity]
class Cuve
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cuve:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'float')]
    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    #[Groups(['cuve:read', 'cuve:write'])]
    private float $niveau_cm;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['cuve:read'])] // 🧠 uniquement en lecture (automatique à la création)
    private \DateTimeImmutable $horodatage;

    public function __construct()
    {
        $this->horodatage = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNiveauCm(): float
    {
        return $this->niveau_cm;
    }

    public function setNiveauCm(float $niveau_cm): self
    {
        $this->niveau_cm = $niveau_cm;
        return $this;
    }

    public function getHorodatage(): \DateTimeImmutable
    {
        return $this->horodatage;
    }

    public function setHorodatage(\DateTimeImmutable $horodatage): self
    {
        $this->horodatage = $horodatage;
        return $this;
    }

}


