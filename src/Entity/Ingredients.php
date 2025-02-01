<?php

namespace App\Entity;

use App\Repository\IngredientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IngredientsRepository::class)]
class Ingredients
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["ingredients.list", "ingredients.show", "recipe.list", "recipe.create", "recipe.update", "recipe.show"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["ingredients.list", "ingredients.show", "recipe.list", "recipe.create", "recipe.update", "recipe.show"])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'ingredients')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["ingredients.show", "ingredients.create", "category.show"])]
    private ?IngredientsCategory $idCategory = null;

    /**
     * @var Collection<int, Recipes>
     */
    #[ORM\ManyToMany(targetEntity: Recipes::class, mappedBy: 'idIngredients')]
    #[Groups(["ingredients.get"])]
    private Collection $recipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getIdCategory(): ?IngredientsCategory
    {
        return $this->idCategory;
    }

    public function setIdCategory(?IngredientsCategory $idCategory): static
    {
        $this->idCategory = $idCategory;

        return $this;
    }

    /**
     * @return Collection<int, Recipes>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipes $recipe): static
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->addIdIngredient($this);
        }

        return $this;
    }

    public function removeRecipe(Recipes $recipe): static
    {
        if ($this->recipes->removeElement($recipe)) {
            $recipe->removeIdIngredient($this);
        }

        return $this;
    }
}
