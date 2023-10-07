<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant extends Utilisateur
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'etudiants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $domaine_etude = null;

    #[ORM\OneToMany(mappedBy: 'ref_etudiant', targetEntity: RendezVous::class, orphanRemoval: true)]
    private Collection $rendezVouses;

    #[ORM\OneToMany(mappedBy: 'ref_etudiant', targetEntity: Inscription::class, orphanRemoval: true)]
    private Collection $inscriptions;

    #[ORM\OneToMany(mappedBy: 'ref_etudiant', targetEntity: Postulation::class, orphanRemoval: true)]
    private Collection $postulations;

    public function __construct()
    {
        $this->rendezVouses = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
        $this->postulations = new ArrayCollection();
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

    public function getDomaineEtude(): ?string
    {
        return $this->domaine_etude;
    }

    public function setDomaineEtude(string $domaine_etude): static
    {
        $this->domaine_etude = $domaine_etude;

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
            $rendezVouse->setRefEtudiant($this);
        }

        return $this;
    }

    public function removeRendezVouse(RendezVous $rendezVouse): static
    {
        if ($this->rendezVouses->removeElement($rendezVouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezVouse->getRefEtudiant() === $this) {
                $rendezVouse->setRefEtudiant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Inscription>
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): static
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions->add($inscription);
            $inscription->setRefEtudiant($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): static
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getRefEtudiant() === $this) {
                $inscription->setRefEtudiant(null);
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
            $postulation->setRefEtudiant($this);
        }

        return $this;
    }

    public function removePostulation(Postulation $postulation): static
    {
        if ($this->postulations->removeElement($postulation)) {
            // set the owning side to null (unless already changed)
            if ($postulation->getRefEtudiant() === $this) {
                $postulation->setRefEtudiant(null);
            }
        }

        return $this;
    }
}
