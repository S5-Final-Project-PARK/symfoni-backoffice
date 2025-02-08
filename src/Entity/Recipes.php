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
    #[Groups(["recipe.create", "recipe.update", "recipe.show"])]
    private Collection $idIngredients;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["recipe.list", "recipe.create", "recipe.update", "recipe.show"])]
    private ?Dishes $Dish = null;

    /**
     * @var Collection<int, RecipeIngredient>
     */
    #[ORM\OneToMany(targetEntity: RecipeIngredient::class, mappedBy: 'recipe')]
    #[Groups(["recipe.list", "recipe.create", "recipe.update", "order.show" ,"recipe.show"])]
    private Collection $recipeIngredients;

    public function __construct()
    {
        $this->idIngredients = new ArrayCollection();
        $this->recipeIngredients = new ArrayCollection();
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

    /**
     * @return Collection<int, RecipeIngredient>
     */
    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if (!$this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients->add($recipeIngredient);
            $recipeIngredient->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if ($this->recipeIngredients->removeElement($recipeIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getRecipe() === $this) {
                $recipeIngredient->setRecipe(null);
            }
        }

        return $this;
    }
}
