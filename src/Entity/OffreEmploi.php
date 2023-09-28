<?php

namespace App\Entity;

use App\Repository\OffreEmploiRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OffreEmploiRepository::class)]
class OffreEmploi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private ?string $titre;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private ?string $description;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private ?string $type_contrat;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?RepresentantH $ref_representant_h;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

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

    public function getTypeContrat(): ?string
    {
        return $this->type_contrat;
    }

    public function setTypeContrat(string $type_contrat): static
    {
        $this->type_contrat = $type_contrat;

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
}
