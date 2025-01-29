<?php

namespace App\Controller\API;

use App\Entity\IngredientsCategory;
use App\Repository\IngredientsCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api/ingredients-category', name: 'api_ingredients-category_')]
class IngredientsCategoryController extends AbstractController
{
    #[Route('/create', name:'create' ,methods: ['POST'])]
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

    #[Route('/list', name:'list', methods: ['GET'])]
    public function list(IngredientsCategoryRepository $repository): JsonResponse
    {
        $categories = $repository->findAll();

        return $this->json($categories, 200, [
            'groups' => 'category.show'
        ]);
    }
}

?>
