<?php

namespace App\Entity;

use App\Repository\PlataformaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlataformaRepository::class)]
class Plataforma
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $plataforma = null;

    #[ORM\ManyToOne(inversedBy: 'marcaPlataforma')]
    private ?Marca $marca = null;

    #[ORM\ManyToMany(targetEntity: Videojoc::class, mappedBy: 'videojoc_plataforma')]
    private Collection $plataforma_videojocs;

    public function __construct()
    {
        $this->plataforma_videojocs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlataforma(): ?string
    {
        return $this->plataforma;
    }

    public function setPlataforma(string $plataforma): self
    {
        $this->plataforma = $plataforma;

        return $this;
    }

    public function getMarca(): ?Marca
    {
        return $this->marca;
    }

    public function setMarca(?Marca $marca): self
    {
        $this->marca = $marca;

        return $this;
    }

    /**
     * @return Collection<int, Videojoc>
     */
    public function getPlataformaVideojocs(): Collection
    {
        return $this->plataforma_videojocs;
    }

    public function addPlataformaVideojoc(Videojoc $plataformaVideojoc): self
    {
        if (!$this->plataforma_videojocs->contains($plataformaVideojoc)) {
            $this->plataforma_videojocs->add($plataformaVideojoc);
            $plataformaVideojoc->addVideojocPlataforma($this);
        }

        return $this;
    }

    public function removePlataformaVideojoc(Videojoc $plataformaVideojoc): self
    {
        if ($this->plataforma_videojocs->removeElement($plataformaVideojoc)) {
            $plataformaVideojoc->removeVideojocPlataforma($this);
        }

        return $this;
    }
}
