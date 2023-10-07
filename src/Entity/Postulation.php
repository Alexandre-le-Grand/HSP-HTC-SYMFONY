<?php

namespace App\Entity;

use App\Repository\PostulationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostulationRepository::class)]
class Postulation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'postulations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OffreEmploi $ref_offre = null;

    #[ORM\ManyToOne(inversedBy: 'postulations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etudiant $ref_etudiant = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRefEtudiant(): ?Etudiant
    {
        return $this->ref_etudiant;
    }

    public function setRefEtudiant(?Etudiant $ref_etudiant): static
    {
        $this->ref_etudiant = $ref_etudiant;

        return $this;
    }
}
