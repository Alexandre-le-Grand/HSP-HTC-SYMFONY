<?php

namespace App\Entity;

use App\Repository\AdministrateurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdministrateurRepository::class)]
class Administrateur extends Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: self::class)]
    private ?self $ref_admin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefAdmin(): ?self
    {
        return $this->ref_admin;
    }

    public function setRefAdmin(?self $ref_admin): static
    {
        $this->ref_admin = $ref_admin;

        return $this;
    }
}
