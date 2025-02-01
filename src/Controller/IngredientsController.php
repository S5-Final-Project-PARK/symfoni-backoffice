<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Entity\IngredientsCategory;
use App\Repository\IngredientsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

final class IngredientsController extends AbstractController
{
    #[Route('/ingredients/create', name: 'create_ingredients', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        // Get data from the request body (JSON payload)
        $data = json_decode($request->getContent(), true);

        // Check if required fields are present
        if (!isset($data['name']) || !isset($data['idCategory'])) {
            return $this->json([
                'error' => 'Missing required fields: name, idCategory'
            ], 400);
        }

        // Find the IngredientsCategory by idCategory
        $category = $em->getRepository(IngredientsCategory::class)->find($data['idCategory']);
        if (!$category) {
            return $this->json([
                'error' => 'Invalid category ID'
            ], 400);
        }

        // Create new Ingredients object and set its properties
        $ingredient = new Ingredients();
        $ingredient->setName($data['name']);
        $ingredient->setIdCategory($category); // Assuming `idCategory` is a reference to IngredientsCategory

        // Persist the ingredient to the database
        $em->persist($ingredient);
        $em->flush();

        // Return a response with the created ingredient data
        return $this->json([
            'message' => 'Ingredient created successfully',
            'ingredient' => [
                'id' => $ingredient->getId(),
                'name' => $ingredient->getName(),
                'category' => $ingredient->getIdCategory()->getName(), // Assuming `getName()` exists in IngredientsCategory
            ]
        ], 201);
    }

    #[Route('/ingredients/list', name: 'list_ingredients', methods: ['GET'])]
    public function list(IngredientsRepository $repository): JsonResponse{
        $category = $repository->findAll();

        return $this->json($category, 200, [], [
            "groups" => ["ingredients.list"]
        ]);
    }

    #[Route('/ingredients/detail/{name}-{id}', name: 'show_ingredients', methods: ['GET'])]
    public function show(string $name, int $id, IngredientsRepository $repository): JsonResponse
    {
        $ingredient = $repository->find($id);

        if (!$ingredient) {
            return $this->json(['error' => 'Ingredient not found'], 404);
        }

        return $this->json($ingredient, 200, [], [
            "groups" => ["ingredients.show"]
        ]);
    }



}
