<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InscriptionRepository::class)]
class Inscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Etudiant::class)]
    private Collection $ref_etudiant;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Conference $ref_conference = null;

    public function __construct()
    {
        $this->ref_etudiant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Etudiant>
     */
    public function getRefEtudiant(): Collection
    {
        return $this->ref_etudiant;
    }

    public function addRefEtudiant(Etudiant $refEtudiant): static
    {
        if (!$this->ref_etudiant->contains($refEtudiant)) {
            $this->ref_etudiant->add($refEtudiant);
        }

        return $this;
    }

    public function removeRefEtudiant(Etudiant $refEtudiant): static
    {
        $this->ref_etudiant->removeElement($refEtudiant);

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
