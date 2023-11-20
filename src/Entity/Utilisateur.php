<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\EntityListeners(['App\EntityListener\UserListener'])]
#[UniqueEntity('email')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 127)]
    #[Assert\NotBlank()]
    private ?string $nom = null;

    #[ORM\Column(length: 127)]
    #[Assert\NotBlank()]
    private ?string $prenom = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email()]
    #[Assert\NotBlank()]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $statut = false;

    private ?string $plainpassword = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank()]
    private ?string $password = 'password';

    #[ORM\Column(type: 'string', length: 100)]
    private $resetToken;


    #[ORM\Column(type: 'json')]
    #[Assert\NotNull]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: 'ref_utilisateur', targetEntity: Conference::class, orphanRemoval: true)]
    private Collection $conferences;

    #[ORM\ManyToOne(inversedBy: 'utilisateurs')]
    private ?Administrateur $ref_admin = null;

    #[ORM\OneToMany(mappedBy: 'id_utilisateur', targetEntity: Etudiant::class, orphanRemoval: true)]
    private Collection $etudiants;

    #[ORM\OneToMany(mappedBy: 'id_utilisateur', targetEntity: RepresentantH::class, orphanRemoval: true)]
    private Collection $representantHs;

    #[ORM\OneToMany(mappedBy: 'id_utilisateur', targetEntity: Administrateur::class, orphanRemoval: true)]
    private Collection $administrateurs;

    public function __construct()
    {
        $this->conferences = new ArrayCollection();
        $this->etudiants = new ArrayCollection();
        $this->representantHs = new ArrayCollection();
        $this->administrateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

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

    public function getIsAccountValidated(): bool
    {
        return $this->statut === true;
    }

    public function getPlainpassword(): ?string
    {
        return $this->plainpassword;
    }

    public function setPlainpassword(?string $plainpassword): void
    {
        $this->plainpassword = $plainpassword;
    }

    /**
     * @return mixed
     */
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

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
            $conference->setRefUtilisateur($this);
        }

        return $this;
    }

    public function removeConference(Conference $conference): static
    {
        if ($this->conferences->removeElement($conference)) {
            // set the owning side to null (unless already changed)
            if ($conference->getRefUtilisateur() === $this) {
                $conference->setRefUtilisateur(null);
            }
        }

        return $this;
    }

    public function getRefAdmin(): ?Administrateur
    {
        return $this->ref_admin;
    }

    public function setRefAdmin(?Administrateur $ref_admin): static
    {
        $this->ref_admin = $ref_admin;

        return $this;
    }

    /**
     * @return Collection<int, Etudiant>
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $etudiant): static
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants->add($etudiant);
            $etudiant->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): static
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getIdUtilisateur() === $this) {
                $etudiant->setIdUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RepresentantH>
     */
    public function getRepresentantHs(): Collection
    {
        return $this->representantHs;
    }

    public function addRepresentantH(RepresentantH $representantH): static
    {
        if (!$this->representantHs->contains($representantH)) {
            $this->representantHs->add($representantH);
            $representantH->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeRepresentantH(RepresentantH $representantH): static
    {
        if ($this->representantHs->removeElement($representantH)) {
            // set the owning side to null (unless already changed)
            if ($representantH->getIdUtilisateur() === $this) {
                $representantH->setIdUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Administrateur>
     */
    public function getAdministrateurs(): Collection
    {
        return $this->administrateurs;
    }

    public function addAdministrateur(Administrateur $administrateur): static
    {
        if (!$this->administrateurs->contains($administrateur)) {
            $this->administrateurs->add($administrateur);
            $administrateur->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeAdministrateur(Administrateur $administrateur): static
    {
        if ($this->administrateurs->removeElement($administrateur)) {
            // set the owning side to null (unless already changed)
            if ($administrateur->getIdUtilisateur() === $this) {
                $administrateur->setIdUtilisateur(null);
            }
        }

        return $this;
    }
}
