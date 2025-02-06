<?php

namespace App\Entity;

use App\Repository\DishesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DishesRepository::class)]
class Dishes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["dish.list", "dish.show", "recipe.list", "recipe.show", "order.show"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["dish.list", "dish.show", "recipe.list", "recipe.show", "order.show"])]
    private ?string $name = null;

    /**
     * @var Collection<int, Recipes>
     */
    #[ORM\OneToMany(targetEntity: Recipes::class, mappedBy: 'Dish')]
    #[Groups(["dish.show"])]
    private Collection $recipes;

    #[ORM\Column(type: Types::DECIMAL, precision: 38, scale: 2)]
    private ?string $price = null;

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
            $recipe->setDish($this);
        }

        return $this;
    }

    public function removeRecipe(Recipes $recipe): static
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getDish() === $this) {
                $recipe->setDish(null);
            }
        }

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }
}
