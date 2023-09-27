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
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Etudiant $ref_etudiant = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Conference $ref_conference = null;

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
