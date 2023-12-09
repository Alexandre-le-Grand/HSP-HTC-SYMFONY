<?php

namespace App\Entity;

use App\Repository\AmphitheatreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: AmphitheatreRepository::class)]
#[UniqueEntity('nom')]
class Amphitheatre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 127)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $nb_places = null;

    #[ORM\OneToMany(mappedBy: 'ref_amphi', targetEntity: Conference::class)]
    private Collection $conferences;

    #[ORM\Column(nullable: true)]
    private ?bool $disponible = null;

    #[ORM\Column(nullable: true, type: 'string')]
    private ?string $heureFin = null;

    public function __construct()
    {
        $this->conferences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNbPlaces(): ?int
    {
        return $this->nb_places;
    }

    public function setNbPlaces(int $nb_places): static
    {
        $this->nb_places = $nb_places;

        return $this;
    }

    /**
     * @return Collection<int, Conference>
     */
    public function getConferences(): Collection
    {
        return $this->conferences;
    }

    public function addConference(Conference $conference): static
    {
        if (!$this->conferences->contains($conference)) {
            $this->conferences->add($conference);
            $conference->setRefAmphi($this);
        }

        return $this;
    }

    public function removeConference(Conference $conference): static
    {
        if ($this->conferences->removeElement($conference)) {
            // set the owning side to null (unless already changed)
            if ($conference->getRefAmphi() === $this) {
                $conference->setRefAmphi(null);
            }
        }

        return $this;
    }

    public function isDisponible(): ?bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): static
    {
        $this->disponible = $disponible;

        return $this;
    }

    public function getHeureFin(): ?string
    {
        return $this->heureFin;
    }

    public function setHeureFin(?string $heureFin): void
    {
        $this->heureFin = $heureFin;
    }


}
