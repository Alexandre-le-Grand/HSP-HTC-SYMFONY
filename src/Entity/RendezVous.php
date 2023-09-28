<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\Date]
    #[Assert\NotNull]
    private ?\DateTimeImmutable $date;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    #[Assert\Time]
    #[Assert\NotNull]
    private ?\DateTimeImmutable $heure;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?bool $statut;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?RepresentantH $ref_representant_h;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etudiant $ref_etudiant;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?OffreEmploi $ref_offre;

    public function getId(): ?int
    {
        return $this->id;
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

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;

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

    public function getRefEtudiant(): ?Etudiant
    {
        return $this->ref_etudiant;
    }

    public function setRefEtudiant(?Etudiant $ref_etudiant): static
    {
        $this->ref_etudiant = $ref_etudiant;

        return $this;
    }

    public function getRefOffre(): ?OffreEmploi
    {
        return $this->ref_offre;
    }

    public function setRefOffre(?OffreEmploi $ref_offre): static
    {
        $this->ref_offre = $ref_offre;

        return $this;
    }
}
