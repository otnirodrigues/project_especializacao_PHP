<?php

namespace App\Entity;

use App\Repository\TransacaoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransacaoRepository::class)]
class Transacao
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $descricao = null;

    #[ORM\Column(length: 255)]
    private ?string $valor = null;

    #[ORM\ManyToOne(inversedBy: 'trasacoes')]
    private ?Conta $trasacaoContas = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $data = null;

    #[ORM\ManyToOne(inversedBy: 'transacaos')]
    private ?Conta $contaDestino = null;

    #[ORM\ManyToOne(inversedBy: 'transacaos')]
    private ?Conta $contaRemetente = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getValor(): ?string
    {
        return $this->valor;
    }

    public function setValor(string $valor): self
    {
        $this->valor = $valor;

        return $this;
    }

    public function getTrasacaoContas(): ?Conta
    {
        return $this->trasacaoContas;
    }

    public function setTrasacaoContas(?Conta $trasacaoContas): self
    {
        $this->trasacaoContas = $trasacaoContas;

        return $this;
    }

    public function getData(): ?\DateTimeInterface
    {
        return $this->data;
    }

    public function setData(\DateTimeInterface $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getContaDestino(): ?Conta
    {
        return $this->contaDestino;
    }

    public function setContaDestino(?Conta $contaDestino): self
    {
        $this->contaDestino = $contaDestino;

        return $this;
    }

    public function getContaRemetente(): ?Conta
    {
        return $this->contaRemetente;
    }

    public function setContaRemetente(?Conta $contaRemetente): self
    {
        $this->contaRemetente = $contaRemetente;

        return $this;
    }

    public function __toString()
    {
        return $this->descricao;
    }
}
