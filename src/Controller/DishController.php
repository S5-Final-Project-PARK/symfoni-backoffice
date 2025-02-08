<?php

namespace App\Controller;

use App\Entity\Dishes;
use App\Repository\DishesRepository;
use App\Repository\RecipesRepository;
use App\Repository\RecipeIngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DishController extends AbstractController
{
    #[Route('/dishes/create', name: 'create_dish', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'], $data['price'])) {
            return $this->json([
                'error' => 'Missing required field: name or price'
            ], 400);
        }

        $verify = $em->getRepository(Dishes::class)->findOneBy(['name' => $data['name']]);
        if($verify){
            return $this->json([
                'error' => 'This Dish exists already'
            ], 400);
        }

        $dish = new Dishes();
        $dish->setName($data['name']);
        $dish->setPrice($data['price']);

        $em->persist($dish);
        $em->flush();

        return $this->json([
            'message' => 'Dish created successfully',
            'dish' => [
                'id' => $dish->getId(),
                'name' => $dish->getName(),
                'price' => $dish->getPrice()
            ]
        ], 201);
    }

    #[Route('/dishes/list', name: 'list_dishes', methods: ['GET'])]
    public function list(DishesRepository $repository): JsonResponse
    {
        $dishes = $repository->findAll();

        if(!$dishes){
            return $this->json([
                'error' => 'No Dishes found'
            ], 400);
        }

        return $this->json($dishes, 200, [], [
            "groups" => ["dish.list"]
        ]);
    }

    #[Route('/dishes/get/{name}-{id}', name: 'get_dish', methods: ['GET'])]
    public function getList(string $name, int $id, DishesRepository $repository): JsonResponse
    {
        $dish = $repository->find($id);

        if (!$dish) {
            return $this->json(['error' => 'Dish not found'], 404);
        }

        return $this->json($dish, 200, [], [
            "groups" => ["dish.show"]
        ]);
    }

    #[Route('/dishes/cancel/{id}', methods: ['DELETE'])]
    public function cancel(int $id, EntityManagerInterface $entityManager, RecipesRepository $recipesRepository, RecipeIngredientRepository $recipeIngredientRepository): Response
    {
        $dish = $entityManager->getRepository(Dishes::class)->find($id);
        if (!$dish) {
            return new JsonResponse(['error' => 'Dish not found'], Response::HTTP_NOT_FOUND);
        }

        // Check if the dish is used in any recipes
        $recipe = $recipesRepository->findOneBy(['dish' => $dish]);
        if ($recipe) {
            // If the dish is part of a recipe, remove the ingredients related to this recipe
            foreach ($recipe->getRecipeIngredients() as $recipeIngredient) {
                $entityManager->remove($recipeIngredient);
            }

            // Remove the recipe itself if needed
            $entityManager->remove($recipe);
        }

        // Finally, remove the dish
        $entityManager->remove($dish);
        $entityManager->flush();
        
        return new JsonResponse(['message' => 'Dish and related recipes/ingredients canceled successfully']);
    }

    #[Route('/dishes/delete/{id}', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager, RecipesRepository $recipesRepository, RecipeIngredientRepository $recipeIngredientRepository): JsonResponse {
        // Find the dish by ID
        $dish = $entityManager->getRepository(Dishes::class)->find($id);
        if (!$dish) {
            return new JsonResponse(['error' => 'Dish not found'], Response::HTTP_NOT_FOUND);
        }

        // Check if the dish is used in any recipes
        $recipe = $recipesRepository->findOneBy(['dish' => $dish]);
        if ($recipe) {
            // If the dish is part of a recipe, remove the ingredients related to this recipe
            foreach ($recipe->getRecipeIngredients() as $recipeIngredient) {
                $entityManager->remove($recipeIngredient);
            }

            // Remove the recipe itself if needed
            $entityManager->remove($recipe);
        }

        // Finally, remove the dish
        $entityManager->remove($dish);
        $entityManager->flush();
        
        return new JsonResponse(['message' => 'Dish and related recipes/ingredients deleted successfully']);
    }

}
