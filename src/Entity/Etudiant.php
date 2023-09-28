<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant extends Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private ?string $domaine_etude = null;

    #[ORM\ManyToOne]
    private ?Administrateur $ref_admin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDomaineEtude(): ?string
    {
        return $this->domaine_etude;
    }

    public function setDomaineEtude(string $domaine_etude): static
    {
        $this->domaine_etude = $domaine_etude;

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
