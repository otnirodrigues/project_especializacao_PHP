<?php

namespace App\Entity;

use App\Repository\GerenteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GerenteRepository::class)]
class Gerente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nome = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToOne(mappedBy: 'gerente', cascade: ['persist', 'remove'])]
    private ?Agencia $agencia = null;

    public function __toString()
    {
        return $this->nome;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAgencia(): ?Agencia
    {
        return $this->agencia;
    }

    public function setAgencia(?Agencia $agencia): self
    {
        // unset the owning side of the relation if necessary
        if ($agencia === null && $this->agencia !== null) {
            $this->agencia->setGerente(null);
        }

        // set the owning side of the relation if necessary
        if ($agencia !== null && $agencia->getGerente() !== $this) {
            $agencia->setGerente($this);
        }

        $this->agencia = $agencia;

        return $this;
    }
}
