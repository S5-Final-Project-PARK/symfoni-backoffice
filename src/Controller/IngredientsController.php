<?php

namespace App\Controller\Api;

use App\Entity\Ingredients;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/ingredients', name: 'api_ingredients_')]
class IngredientController extends AbstractController
{
    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'])) {
            return $this->json(['error' => 'Missing name'], 400);
        }

        $ingredient = new Ingredients();
        $ingredient->setName($data['name']);

        $em->persist($ingredient);
        $em->flush();

        return $this->json(['message' => 'Ingredient created!', 'id' => $ingredient->getId()], 201);
    }
}
