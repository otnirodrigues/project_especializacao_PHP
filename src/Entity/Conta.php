<?php

namespace App\Entity;

use App\Repository\ContaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContaRepository::class)]
class Conta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numero = null;

    #[ORM\Column]
    private ?float $saldo = null;

    #[ORM\ManyToOne(inversedBy: 'contas')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'contas')]
    private ?Agencia $agencia = null;

    #[ORM\OneToMany(mappedBy: 'trasacaoContas', targetEntity: Transacao::class)]
    private Collection $trasacoes;

    #[ORM\ManyToOne(inversedBy: 'contas')]
    private ?TipoConta $tipoConta = null;

    public function __construct()
    {
        $this->trasacoes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getSaldo(): ?float
    {
        return $this->saldo;
    }

    public function setSaldo(float $saldo): self
    {
        $this->saldo = $saldo;

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
        $this->agencia = $agencia;

        return $this;
    }

    /**
     * @return Collection<int, Transacao>
     */
    public function getTrasacoes(): Collection
    {
        return $this->trasacoes;
    }

    public function addTrasaco(Transacao $trasaco): self
    {
        if (!$this->trasacoes->contains($trasaco)) {
            $this->trasacoes->add($trasaco);
            $trasaco->setTrasacaoContas($this);
        }

        return $this;
    }

    public function removeTrasaco(Transacao $trasaco): self
    {
        if ($this->trasacoes->removeElement($trasaco)) {
            // set the owning side to null (unless already changed)
            if ($trasaco->getTrasacaoContas() === $this) {
                $trasaco->setTrasacaoContas(null);
            }
        }

        return $this;
    }

    public function getTipoConta(): ?TipoConta
    {
        return $this->tipoConta;
    }

    public function setTipoConta(?TipoConta $tipoConta): self
    {
        $this->tipoConta = $tipoConta;

        return $this;
    }
}
