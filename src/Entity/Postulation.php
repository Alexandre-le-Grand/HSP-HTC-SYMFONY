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
    private ?OffreEmploi $refOffre = null;

    #[ORM\ManyToOne(inversedBy: 'postulations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etudiant $refEtudiant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefOffre(): ?OffreEmploi
    {
        return $this->refOffre;
    }

    public function setRefOffre(?OffreEmploi $refOffre): static
    {
        $this->refOffre = $refOffre;

        return $this;
    }

    public function getRefEtudiant(): ?Etudiant
    {
        return $this->refEtudiant;
    }

    public function setRefEtudiant(?Etudiant $refEtudiant): self
    {
        $this->refEtudiant = $refEtudiant;

        return $this;
    }
}
