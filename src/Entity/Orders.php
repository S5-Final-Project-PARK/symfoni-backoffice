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
    private ?string $idClient = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["order.show"])]
    private ?Dishes $Dish = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(["order.show"])]
    private ?\DateTimeInterface $Date = null;

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
}
