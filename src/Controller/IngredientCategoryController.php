<?php

namespace App\Controller;

use App\Entity\IngredientsCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

final class IngredientCategoryController extends AbstractController
{
    #[Route('/ingredients-category/create', name: 'create_ingredients_category', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        // Get the data from the request body (JSON payload)
        $data = json_decode($request->getContent(), true);

        // Check if the 'name' field is provided
        if (!isset($data['name'])) {
            return $this->json([
                'error' => 'Missing required field: name'
            ], 400);
        }

        // Create a new IngredientsCategory object and set its name
        $category = new IngredientsCategory();
        $category->setName($data['name']);

        // Persist the category to the database
        $em->persist($category);
        $em->flush();

        // Return a response with the created category data
        return $this->json([
            'message' => 'IngredientsCategory created successfully',
            'category' => [
                'id' => $category->getId(),
                'name' => $category->getName(),
            ]
        ], 201);
    }
}
