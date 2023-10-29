<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $date = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $heure = null;

    #[ORM\Column]
    private ?bool $statut = null;

    #[ORM\ManyToOne(inversedBy: 'rendezVouses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RepresentantH $ref_representantH = null;

    #[ORM\ManyToOne(inversedBy: 'rendezVouses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etudiant $ref_etudiant = null;

    #[ORM\ManyToOne(inversedBy: 'rendezVouses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OffreEmploi $ref_offre = null;

    #[ORM\ManyToOne(targetEntity: Postulation::class)]
    #[ORM\JoinColumn(name: "postulation_id", referencedColumnName: "id", nullable: false)]
    private $postulation;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): void
    {
        $this->date = $date;
    }

    public function getHeure(): ?string
    {
        return $this->heure;
    }

    public function setHeure(?string $heure): void
    {
        $this->heure = $heure;
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
        return $this->ref_representantH;
    }

    public function setRefRepresentantH(?RepresentantH $ref_representantH): static
    {
        $this->ref_representantH = $ref_representantH;

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

    /**
     * @return mixed
     */
    public function getPostulation()
    {
        return $this->postulation;
    }

    /**
     * @param mixed $postulation
     */
    public function setPostulation($postulation): void
    {
        $this->postulation = $postulation;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->getPostulation()->getEtudiant();
    }



}
