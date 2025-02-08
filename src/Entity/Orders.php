<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["order.show"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::BIGINT)]
    #[Groups(["order.show"])]
    private ?string $unit = null;

    #[ORM\Column(type: Types::BIGINT)]
    #[Groups(["order.show"])]
    private ?string $unit_price = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["order.show"])]
    private ?Dishes $Dish = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(["order.show"])]
    private ?\DateTimeInterface $Date = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(["order.show"])]
    private ?string $email = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups(["order.verif"])]
    private bool $confirmation = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdClient(): ?string
    {
        return $this->idClient;
    }

    public function setIdClient(string $idClient): static
    {
        $this->idClient = $idClient;

        return $this;
    }

    public function getDish(): ?Dishes
    {
        return $this->Dish;
    }

    public function setDish(?Dishes $Dish): static
    {
        $this->Dish = $Dish;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): static
    {
        $this->Date = $Date;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    public function setUnitPrice(string $unit_price): static
    {
        $this->unit_price = $unit_price;

        return $this;
    }

    public function getUnitPrice(): ?string
    {
        return $this->unit_price;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isConfirmation(): bool
    {
        return $this->confirmation;
    }

    public function setConfirmation(bool $confirmation): static
    {
        $this->confirmation = $confirmation;

        return $this;
    }
}
