<?php

namespace App\Entity;

use App\Repository\UsuariRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UsuariRepository::class)]
class Usuari implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Length(min: 3,max:255)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $ban = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 3,max:255)]
    private ?string $avatar = null;

    #[ORM\OneToMany(mappedBy: 'usuari_votacio', targetEntity: Votacio::class)]
    private Collection $votacions;

    #[ORM\OneToMany(mappedBy: 'Usuari', targetEntity: Comprar::class)]
    private Collection $comprars;

    public function __construct()
    {
        $this->votacions = new ArrayCollection();
        $this->comprars = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isBan(): ?bool
    {
        return $this->ban;
    }

    public function setBan(bool $ban): self
    {
        $this->ban = $ban;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection<int, Votacio>
     */
    public function getVotacions(): Collection
    {
        return $this->votacions;
    }

    public function addVotacion(Votacio $votacion): self
    {
        if (!$this->votacions->contains($votacion)) {
            $this->votacions->add($votacion);
            $votacion->setUsuariVotacio($this);
        }

        return $this;
    }

    public function removeVotacion(Votacio $votacion): self
    {
        if ($this->votacions->removeElement($votacion)) {
            // set the owning side to null (unless already changed)
            if ($votacion->getUsuariVotacio() === $this) {
                $votacion->setUsuariVotacio(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comprar>
     */
    public function getComprars(): Collection
    {
        return $this->comprars;
    }

    public function addComprar(Comprar $comprar): self
    {
        if (!$this->comprars->contains($comprar)) {
            $this->comprars->add($comprar);
            $comprar->setUsuari($this);
        }

        return $this;
    }

    public function removeComprar(Comprar $comprar): self
    {
        if ($this->comprars->removeElement($comprar)) {
            // set the owning side to null (unless already changed)
            if ($comprar->getUsuari() === $this) {
                $comprar->setUsuari(null);
            }
        }

        return $this;
    }
}
