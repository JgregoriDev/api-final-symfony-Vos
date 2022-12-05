<?php

namespace App\Entity;

use App\Repository\VideojocRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VideojocRepository::class)]
class Videojoc
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3,max:255)]
    private ?string $titul = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 3,max:255)]
    private ?string $descripcio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaEstreno = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $portada = null;

    #[ORM\Column]
    #[Assert\Length(min: 0,max:300)]
    private ?int $cantitat = null;

    #[ORM\Column]
    #[Assert\Length(min: 0,max:300)]
    private ?int $preu = null;

    #[ORM\ManyToMany(targetEntity: Plataforma::class, inversedBy: 'plataforma_videojocs')]
    private Collection $videojoc_plataforma;

    #[ORM\ManyToMany(targetEntity: Genere::class, mappedBy: 'genere_videojoc')]
    private Collection $generes;

    #[ORM\OneToMany(mappedBy: 'videojoc', targetEntity: Votacio::class)]
    private Collection $votacions_joc;

    public function __construct()
    {
        $this->videojoc_plataforma = new ArrayCollection();
        $this->generes = new ArrayCollection();
        $this->votacions_joc = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitul(): ?string
    {
        return $this->titul;
    }

    public function setTitul(string $titul): self
    {
        $this->titul = $titul;

        return $this;
    }

    public function getDescripcio(): ?string
    {
        return $this->descripcio;
    }

    public function setDescripcio(?string $descripcio): self
    {
        $this->descripcio = $descripcio;

        return $this;
    }

    public function getFechaEstreno(): ?\DateTimeInterface
    {
        return $this->fechaEstreno;
    }

    public function setFechaEstreno(?\DateTimeInterface $fechaEstreno): self
    {
        $this->fechaEstreno = $fechaEstreno;

        return $this;
    }

    public function getPortada(): ?string
    {
        return $this->portada;
    }

    public function setPortada(?string $portada): self
    {
        $this->portada = $portada;

        return $this;
    }

    public function getCantitat(): ?int
    {
        return $this->cantitat;
    }

    public function setCantitat(int $cantitat): self
    {
        $this->cantitat = $cantitat;

        return $this;
    }

    public function getPreu(): ?int
    {
        return $this->preu;
    }

    public function setPreu(int $preu): self
    {
        $this->preu = $preu;

        return $this;
    }

    /**
     * @return Collection<int, Plataforma>
     */
    public function getVideojocPlataforma(): Collection
    {
        return $this->videojoc_plataforma;
    }

    public function addVideojocPlataforma(Plataforma $videojocPlataforma): self
    {
        if (!$this->videojoc_plataforma->contains($videojocPlataforma)) {
            $this->videojoc_plataforma->add($videojocPlataforma);
        }

        return $this;
    }

    public function removeVideojocPlataforma(Plataforma $videojocPlataforma): self
    {
        $this->videojoc_plataforma->removeElement($videojocPlataforma);

        return $this;
    }

    /**
     * @return Collection<int, Genere>
     */
    public function getGeneres(): Collection
    {
        return $this->generes;
    }

    public function addGenere(Genere $genere): self
    {
        if (!$this->generes->contains($genere)) {
            $this->generes->add($genere);
            $genere->addGenereVideojoc($this);
        }

        return $this;
    }

    public function removeGenere(Genere $genere): self
    {
        if ($this->generes->removeElement($genere)) {
            $genere->removeGenereVideojoc($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Votacio>
     */
    public function getVotacionsJoc(): Collection
    {
        return $this->votacions_joc;
    }

    public function addVotacionsJoc(Votacio $votacionsJoc): self
    {
        if (!$this->votacions_joc->contains($votacionsJoc)) {
            $this->votacions_joc->add($votacionsJoc);
            $votacionsJoc->setVideojoc($this);
        }

        return $this;
    }

    public function removeVotacionsJoc(Votacio $votacionsJoc): self
    {
        if ($this->votacions_joc->removeElement($votacionsJoc)) {
            // set the owning side to null (unless already changed)
            if ($votacionsJoc->getVideojoc() === $this) {
                $votacionsJoc->setVideojoc(null);
            }
        }

        return $this;
    }
}
