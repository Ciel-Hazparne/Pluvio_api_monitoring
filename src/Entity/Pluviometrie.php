<?php

namespace App\Entity;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;

#[ApiResource
(operations: [new Get(), new GetCollection(), new Post()])]
#[ORM\Entity]
class Pluviometrie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'float')]
    private float $pluvio_heure;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $horodatage;

    public function getId(): ?int { return $this->id; }
    public function getPluvioHeure(): float { return $this->pluvio_heure; }
    public function setPluvioHeure(float $pluvio_heure): self { $this->pluvio_heure = $pluvio_heure; return $this; }
    public function getHorodatage(): \DateTimeImmutable { return $this->horodatage; }
    public function setHorodatage(\DateTimeImmutable $horodatage): self { $this->horodatage = $horodatage; return $this; }
    public function __construct()
    {
        $this->horodatage = new \DateTimeImmutable();
    }
}