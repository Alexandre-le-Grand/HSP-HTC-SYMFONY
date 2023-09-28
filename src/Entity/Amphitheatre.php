<?php

namespace App\Entity;

use App\Repository\AmphitheatreRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AmphitheatreRepository::class)]
class Amphitheatre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private ?string $nom;

    #[ORM\Column]
    #[Assert\Positive]
    #[Assert\NotNull]
    private ?int $nb_places;

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
}
