<?php

namespace App\Entity;

use App\Repository\VotacioRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VotacioRepository::class)]
class Votacio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $votacio = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $missatge = null;

    #[ORM\ManyToOne(inversedBy: 'votacions')]
    private ?Usuari $usuari_votacio = null;

    #[ORM\ManyToOne(inversedBy: 'votacions_joc')]
    private ?Videojoc $videojoc = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVotacio(): ?int
    {
        return $this->votacio;
    }

    public function setVotacio(int $votacio): self
    {
        $this->votacio = $votacio;

        return $this;
    }

    public function getMissatge(): ?string
    {
        return $this->missatge;
    }

    public function setMissatge(?string $missatge): self
    {
        $this->missatge = $missatge;

        return $this;
    }

    public function getUsuariVotacio(): ?Usuari
    {
        return $this->usuari_votacio;
    }

    public function setUsuariVotacio(?Usuari $usuari_votacio): self
    {
        $this->usuari_votacio = $usuari_votacio;

        return $this;
    }

    public function getVideojoc(): ?Videojoc
    {
        return $this->videojoc;
    }

    public function setVideojoc(?Videojoc $videojoc): self
    {
        $this->videojoc = $videojoc;

        return $this;
    }
}
