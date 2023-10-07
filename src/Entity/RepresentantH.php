<?php

namespace App\Entity;

use App\Repository\RepresentantHRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RepresentantHRepository::class)]
class RepresentantH extends Utilisateur
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'representantHs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_hopital = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\OneToMany(mappedBy: 'ref_representant_h', targetEntity: OffreEmploi::class, orphanRemoval: true)]
    private Collection $offreEmplois;

    #[ORM\OneToMany(mappedBy: 'ref_representantH', targetEntity: RendezVous::class, orphanRemoval: true)]
    private Collection $rendezVouses;

    public function __construct()
    {
        $this->offreEmplois = new ArrayCollection();
        $this->rendezVouses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?Utilisateur $id): static
    {
        $this->id = $id;

        return $this;
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

    /**
     * @return Collection<int, OffreEmploi>
     */
    public function getOffreEmplois(): Collection
    {
        return $this->offreEmplois;
    }

    public function addOffreEmploi(OffreEmploi $offreEmploi): static
    {
        if (!$this->offreEmplois->contains($offreEmploi)) {
            $this->offreEmplois->add($offreEmploi);
            $offreEmploi->setRefRepresentantH($this);
        }

        return $this;
    }

    public function removeOffreEmploi(OffreEmploi $offreEmploi): static
    {
        if ($this->offreEmplois->removeElement($offreEmploi)) {
            // set the owning side to null (unless already changed)
            if ($offreEmploi->getRefRepresentantH() === $this) {
                $offreEmploi->setRefRepresentantH(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVouses(): Collection
    {
        return $this->rendezVouses;
    }

    public function addRendezVouse(RendezVous $rendezVouse): static
    {
        if (!$this->rendezVouses->contains($rendezVouse)) {
            $this->rendezVouses->add($rendezVouse);
            $rendezVouse->setRefRepresentantH($this);
        }

        return $this;
    }

    public function removeRendezVouse(RendezVous $rendezVouse): static
    {
        if ($this->rendezVouses->removeElement($rendezVouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezVouse->getRefRepresentantH() === $this) {
                $rendezVouse->setRefRepresentantH(null);
            }
        }

        return $this;
    }
}
