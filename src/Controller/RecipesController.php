<?php

namespace App\Controller;

use App\Entity\Recipes;
use App\Entity\RecipeIngredient;
use App\Repository\RecipesRepository;
use App\Repository\DishesRepository;
use App\Repository\IngredientsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

final class RecipesController extends AbstractController
{
    #[Route('/recipes/create', name: 'create_recipe', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, DishesRepository $dishesRepository, IngredientsRepository $ingredientsRepository): JsonResponse 
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['error' => 'Invalid JSON'], 400);
        }

        if (!isset($data['dish_id'], $data['ingredients']) || !is_array($data['ingredients'])) {
            return $this->json(['error' => 'Missing or invalid fields'], 400);
        }

        $dish = $dishesRepository->find($data['dish_id']);
        if (!$dish) {
            return $this->json(['error' => 'Dish not found'], 404);
        }

        $recipe = new Recipes();
        $recipe->setDish($dish);

        foreach ($data['ingredients'] as $ingredientData) {
            if (!isset($ingredientData['id'], $ingredientData['quantity'])) {
                return $this->json(['error' => 'Each ingredient must have an id and quantity'], 400);
            }

            $ingredient = $ingredientsRepository->find($ingredientData['id']);
            if (!$ingredient) {
                return $this->json(['error' => "Ingredient with ID {$ingredientData['id']} not found"], 404);
            }

            $recipeIngredient = new RecipeIngredient();
            $recipeIngredient->setIngredients($ingredient);
            $recipeIngredient->setQuantity($ingredientData['quantity']);

            $recipe->addRecipeIngredient($recipeIngredient);
        }

        $entityManager->persist($recipe);
        $entityManager->flush();

        return $this->json($recipe, 201, [], ['groups' => ['recipe.create']]);
    }


    #[Route('/recipe/list', name: 'list_recipe', methods: ['GET'])]
    public function list(RecipesRepository $repository): JsonResponse{
        $category = $repository->findAll();

        return $this->json($category, 200, [], [
            "groups" => ["recipe.show"]
        ]);
    }

}
