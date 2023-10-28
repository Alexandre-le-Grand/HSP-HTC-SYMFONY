<?php

namespace App\Entity;

use App\Repository\ConferenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: ConferenceRepository::class)]
class Conference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 127)]
    private ?string $nom = null;

    #[ORM\Column(length: 127)]
    private ?string $description = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $date = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $heure = null;

    #[ORM\Column(type: Types::STRING, length: 5)]
    private ?string $duree = null;

    #[ORM\Column]
    private ?bool $statut = null;

    #[ORM\ManyToOne(inversedBy: 'conferences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $ref_utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'conferences')]
    private ?Amphitheatre $ref_amphi = null;

    #[ORM\OneToMany(mappedBy: 'ref_conference', targetEntity: Inscription::class, orphanRemoval: true)]
    private Collection $inscriptions;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getHeure(): ?string
    {
        return $this->heure;
    }

    public function setHeure(string $heure): static
    {
        $this->heure = $heure;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): static
    {
        $this->duree = $duree;

        return $this;
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

    public function getRefUtilisateur(): ?Utilisateur
    {
        return $this->ref_utilisateur;
    }

    public function setRefUtilisateur(?Utilisateur $ref_utilisateur): static
    {
        $this->ref_utilisateur = $ref_utilisateur;

        return $this;
    }

    public function getRefAmphi(): ?Amphitheatre
    {
        return $this->ref_amphi;
    }

    public function setRefAmphi(?Amphitheatre $ref_amphi): static
    {
        $this->ref_amphi = $ref_amphi;

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
            $inscription->setRefConference($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): static
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getRefConference() === $this) {
                $inscription->setRefConference(null);
            }
        }

        return $this;
    }

    /**
     * @Assert\Callback(groups={"conference_heure"})
     */
    public function validateHeure(ExecutionContextInterface $context)
    {
        $heure = $this->heure;
        if ($heure < '08:00' || $heure > '12:00') {
            $context
                ->buildViolation('L\'heure de la conférence doit être entre 08:00 et 12:00.')
                ->atPath('heure')
                ->addViolation();
        }
    }
}
