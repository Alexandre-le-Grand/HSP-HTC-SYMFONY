<?php

namespace App\Entity;

use App\Repository\CreationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CreationRepository::class)]
class Creation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\ManyToOne]
    private ?Etudiant $ref_etudiant;

    #[ORM\ManyToOne]
    private ?RepresentantH $ref_representant_h;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Conference $ref_conference;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRefRepresentantH(): ?RepresentantH
    {
        return $this->ref_representant_h;
    }

    public function setRefRepresentantH(?RepresentantH $ref_representant_h): static
    {
        $this->ref_representant_h = $ref_representant_h;

        return $this;
    }

    public function getRefConference(): ?Conference
    {
        return $this->ref_conference;
    }

    public function setRefConference(Conference $ref_conference): static
    {
        $this->ref_conference = $ref_conference;

        return $this;
    }
}