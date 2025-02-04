<?php

namespace App\Controller;

use App\Entity\Recipes;
use App\Repository\RecipesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

final class RecipesController extends AbstractController
{
    #[Route('/recipe/list', name: 'list_recipe', methods: ['GET'])]
    public function list(RecipesRepository $repository): JsonResponse{
        $category = $repository->findAll();

        return $this->json($category, 200, [], [
            "groups" => ["recipe.show"]
        ]);
    }

}
