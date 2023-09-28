<?php

namespace App\Entity;

use App\Repository\ConferenceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ConferenceRepository::class)]
class Conference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private ?string $nom;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private ?string $description;

    #[ORM\Column]
    #[Assert\Date]
    private ?\DateTimeImmutable $date;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    #[Assert\Time]
    private ?\DateTimeImmutable $heure;

    #[ORM\Column]
    #[Assert\Positive]
    private ?\DateInterval $duree;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?bool $statut;

    #[ORM\ManyToOne]
    private ?Administrateur $ref_admin;

    #[ORM\ManyToOne]
    private ?RepresentantH $ref_representant_h;

    #[ORM\ManyToOne]
    private ?Amphitheatre $ref_amphi;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
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

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getHeure(): ?\DateTimeImmutable
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeImmutable $heure): static
    {
        $this->heure = $heure;

        return $this;
    }

    public function getDuree(): ?\DateInterval
    {
        return $this->duree;
    }

    public function setDuree(\DateInterval $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getRefAdmin(): ?Administrateur
    {
        return $this->ref_admin;
    }

    public function setRefAdmin(?Administrateur $ref_admin): static
    {
        $this->ref_admin = $ref_admin;

        return $this;
    }

    public function getRefRepresentantH(): ?RepresentantH
    {
        return $this->ref_representant_h;
    }

    public function setRefRepresentantH(?RepresentantH $ref_representant_h): static
    {
        $this->ref_representant_h = $ref_representant_h;

        return $this;
    }

    public function getRefAmphi(): ?Amphitheatre
    {
        return $this->ref_amphi;
    }

    public function setRefAmphi(?Amphitheatre $ref_amphi): static
    {
        $this->ref_amphi = $ref_amphi;

        return $this;
    }
}
