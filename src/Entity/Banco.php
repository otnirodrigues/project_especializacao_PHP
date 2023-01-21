<?php

namespace App\Entity;

use App\Repository\BancoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BancoRepository::class)]
class Banco
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nome = null;

    #[ORM\OneToMany(mappedBy: 'banco', targetEntity: Agencia::class)]
    private Collection $agencia;

    public function __construct()
    {
        $this->agencia = new ArrayCollection();
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

    /**
     * @return Collection<int, Agencia>
     */
    public function getAgencia(): Collection
    {
        return $this->agencia;
    }

    public function addAgencium(Agencia $agencium): self
    {
        if (!$this->agencia->contains($agencium)) {
            $this->agencia->add($agencium);
            $agencium->setBanco($this);
        }

        return $this;
    }

    public function removeAgencium(Agencia $agencium): self
    {
        if ($this->agencia->removeElement($agencium)) {
            // set the owning side to null (unless already changed)
            if ($agencium->getBanco() === $this) {
                $agencium->setBanco(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nome;
    }
}
