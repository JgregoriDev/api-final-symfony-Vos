<?php

namespace App\Entity;

use App\Repository\MarcaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MarcaRepository::class)]
class Marca
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $marca = null;

    #[ORM\OneToMany(mappedBy: 'marca', targetEntity: Plataforma::class)]
    private Collection $marcaPlataforma;

    public function __construct()
    {
        $this->marcaPlataforma = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarca(): ?string
    {
        return $this->marca;
    }

    public function setMarca(string $marca): self
    {
        $this->marca = $marca;

        return $this;
    }

    /**
     * @return Collection<int, Plataforma>
     */
    public function getMarcaPlataforma(): Collection
    {
        return $this->marcaPlataforma;
    }

    public function addMarcaPlataforma(Plataforma $marcaPlataforma): self
    {
        if (!$this->marcaPlataforma->contains($marcaPlataforma)) {
            $this->marcaPlataforma->add($marcaPlataforma);
            $marcaPlataforma->setMarca($this);
        }

        return $this;
    }

    public function removeMarcaPlataforma(Plataforma $marcaPlataforma): self
    {
        if ($this->marcaPlataforma->removeElement($marcaPlataforma)) {
            // set the owning side to null (unless already changed)
            if ($marcaPlataforma->getMarca() === $this) {
                $marcaPlataforma->setMarca(null);
            }
        }

        return $this;
    }
}
