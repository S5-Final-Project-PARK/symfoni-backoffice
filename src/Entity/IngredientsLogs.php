<?php

namespace App\Entity;

use App\Repository\IngredientsLogsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IngredientsLogsRepository::class)]
class IngredientsLogs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["logs.show"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'oldQuantity')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["logs.show"])]
    private ?Ingredients $Ingredients = null;

    #[ORM\Column]
    #[Groups(["logs.show"])]
    private ?int $oldQuantity = null;

    #[ORM\Column]
    #[Groups(["logs.show"])]
    private ?int $newQuantity = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["logs.show"])]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIngredients(): ?Ingredients
    {
        return $this->Ingredients;
    }

    public function setIngredients(?Ingredients $Ingredients): static
    {
        $this->Ingredients = $Ingredients;

        return $this;
    }

    public function getOldQuantity(): ?int
    {
        return $this->oldQuantity;
    }

    public function setOldQuantity(int $oldQuantity): static
    {
        $this->oldQuantity = $oldQuantity;

        return $this;
    }

    public function getNewQuantity(): ?int
    {
        return $this->newQuantity;
    }

    public function setNewQuantity(int $newQuantity): static
    {
        $this->newQuantity = $newQuantity;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
