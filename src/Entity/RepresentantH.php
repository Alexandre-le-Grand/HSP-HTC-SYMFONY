<?php

namespace App\Entity;

use App\Repository\RepresentantHRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RepresentantHRepository::class)]
class RepresentantH extends Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_hopital = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\ManyToOne]
    private ?Administrateur $ref_admin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomHopital(): ?string
    {
        return $this->nom_hopital;
    }

    public function setNomHopital(string $nom_hopital): static
    {
        $this->nom_hopital = $nom_hopital;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

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
}
