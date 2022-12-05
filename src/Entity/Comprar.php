<?php

namespace App\Entity;

use App\Repository\ComprarRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComprarRepository::class)]
class Comprar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'comprars')]
    private ?Usuari $Usuari = null;

    #[ORM\Column]
    private array $Productes = [];

    #[ORM\Column]
    private ?int $Preu = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $DataCompra = null;

    #[ORM\Column]
    private ?bool $EstatPagament = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuari(): ?Usuari
    {
        return $this->Usuari;
    }

    public function setUsuari(?Usuari $Usuari): self
    {
        $this->Usuari = $Usuari;

        return $this;
    }

    public function getProductes(): array
    {
        return $this->Productes;
    }

    public function setProductes(array $Productes): self
    {
        $this->Productes = $Productes;

        return $this;
    }

    public function getPreu(): ?int
    {
        return $this->Preu;
    }

    public function setPreu(int $Preu): self
    {
        $this->Preu = $Preu;

        return $this;
    }

    public function getDataCompra(): ?\DateTimeInterface
    {
        return $this->DataCompra;
    }

    public function setDataCompra(?\DateTimeInterface $DataCompra): self
    {
        $this->DataCompra = $DataCompra;

        return $this;
    }

    public function isEstatPagament(): ?bool
    {
        return $this->EstatPagament;
    }

    public function setEstatPagament(bool $EstatPagament): self
    {
        $this->EstatPagament = $EstatPagament;

        return $this;
    }
}
