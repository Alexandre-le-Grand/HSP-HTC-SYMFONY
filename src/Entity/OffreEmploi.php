<?php

namespace App\Entity;

use App\Repository\OffreEmploiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffreEmploiRepository::class)]
class OffreEmploi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 127)]
    private ?string $titre = null;

    #[ORM\Column(length: 127)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: TypeOffre::class)]
    #[ORM\JoinColumn(name: "type_contrat_id", referencedColumnName: "id")]
    private ?TypeOffre  $type_contrat = null;

    #[ORM\ManyToOne(inversedBy: 'offreEmplois')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RepresentantH $ref_representant_h = null;

    #[ORM\OneToMany(mappedBy: 'refOffre', targetEntity: RendezVous::class, orphanRemoval: true)]
    private Collection $rendezVouses;

    #[ORM\OneToMany(mappedBy: 'refOffre', targetEntity: Postulation::class, orphanRemoval: true)]
    private Collection $postulations;

    public function __construct()
    {
        $this->rendezVouses = new ArrayCollection();
        $this->postulations = new ArrayCollection();
    }

    public function isAlreadyAppliedBy(Etudiant $etudiant): bool
    {
        foreach ($this->postulations as $postulation) {
            if ($postulation->getRefEtudiant() === $etudiant) {
                return true;
            }
        }

        return false;
    }

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

    public function getTypeContrat(): ?TypeOffre
    {
        return $this->type_contrat;
    }

    public function setTypeContrat(?TypeOffre $type_contrat): void
    {
        $this->type_contrat = $type_contrat;
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
            $rendezVouse->setRefOffre($this);
        }

        return $this;
    }

    public function removeRendezVouse(RendezVous $rendezVouse): static
    {
        if ($this->rendezVouses->removeElement($rendezVouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezVouse->getRefOffre() === $this) {
                $rendezVouse->setRefOffre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Postulation>
     */
    public function getPostulations(): Collection
    {
        return $this->postulations;
    }

    public function addPostulation(Postulation $postulation): static
    {
        if (!$this->postulations->contains($postulation)) {
            $this->postulations->add($postulation);
            $postulation->setRefOffre($this);
        }

        return $this;
    }

    public function removePostulation(Postulation $postulation): static
    {
        if ($this->postulations->removeElement($postulation)) {
            // set the owning side to null (unless already changed)
            if ($postulation->getRefOffre() === $this) {
                $postulation->setRefOffre(null);
            }
        }

        return $this;
    }
}
