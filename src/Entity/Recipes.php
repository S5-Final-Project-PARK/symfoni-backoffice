<?php

namespace App\Entity;

use App\Repository\RecipesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RecipesRepository::class)]
class Recipes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["recipe.list", "recipe.create", "recipe.update", "recipe.show"])]
    private ?int $id = null;

    /**
     * @var Collection<int, Ingredients>
     */
    #[ORM\ManyToMany(targetEntity: Ingredients::class, inversedBy: 'recipes')]
    #[Groups(["recipe.list", "recipe.create", "recipe.update", "recipe.show"])]
    private Collection $idIngredients;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["recipe.list", "recipe.create", "recipe.update", "recipe.show"])]
    private ?Dishes $Dish = null;

    public function __construct()
    {
        $this->idIngredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Ingredients>
     */
    public function getIdIngredients(): Collection
    {
        return $this->idIngredients;
    }

    public function addIdIngredient(Ingredients $idIngredient): static
    {
        if (!$this->idIngredients->contains($idIngredient)) {
            $this->idIngredients->add($idIngredient);
        }

        return $this;
    }

    public function removeIdIngredient(Ingredients $idIngredient): static
    {
        $this->idIngredients->removeElement($idIngredient);

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
}
