<?php

namespace App\Entity;

use App\Repository\GenereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenereRepository::class)]
class Genere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $genere = null;

    #[ORM\ManyToMany(targetEntity: Videojoc::class, inversedBy: 'generes')]
    private Collection $genere_videojoc;

    public function __construct()
    {
        $this->genere_videojoc = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGenere(): ?string
    {
        return $this->genere;
    }

    public function setGenere(string $genere): self
    {
        $this->genere = $genere;

        return $this;
    }

    /**
     * @return Collection<int, Videojoc>
     */
    public function getGenereVideojoc(): Collection
    {
        return $this->genere_videojoc;
    }

    public function addGenereVideojoc(Videojoc $genereVideojoc): self
    {
        if (!$this->genere_videojoc->contains($genereVideojoc)) {
            $this->genere_videojoc->add($genereVideojoc);
        }

        return $this;
    }

    public function removeGenereVideojoc(Videojoc $genereVideojoc): self
    {
        $this->genere_videojoc->removeElement($genereVideojoc);

        return $this;
    }
}
