<?php

namespace App\Controller;

use App\Entity\Dishes;
use App\Repository\DishesRepository;
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

        if (!isset($data['name'])) {
            return $this->json([
                'error' => 'Missing required field: name'
            ], 400);
        }

        $dish = new Dishes();
        $dish->setName($data['name']);

        $em->persist($dish);
        $em->flush();

        return $this->json([
            'message' => 'Dish created successfully',
            'dish' => [
                'id' => $dish->getId(),
                'name' => $dish->getName(),
            ]
        ], 201);
    }

    #[Route('/dishes/list', name: 'list_dishes', methods: ['GET'])]
    public function list(DishesRepository $repository): JsonResponse
    {
        $dishes = $repository->findAll();

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
    public function cancel(int $id, EntityManagerInterface $entityManager): Response
    {
        $dish = $entityManager->getRepository(Dishes::class)->find($id);
        
        if (!$dish) {
            return new JsonResponse(['error' => 'Dish not found'], Response::HTTP_NOT_FOUND);
        }
        
        $entityManager->remove($dish);
        $entityManager->flush();
        
        return new JsonResponse(['message' => 'Dish deleted successfully']);
    }

    #[Route('/dishes/delete/{id}', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $dish = $entityManager->getRepository(Dishes::class)->find($id);
        
        if (!$dish) {
            return new JsonResponse(['error' => 'Dish not found'], Response::HTTP_NOT_FOUND);
        }
        
        $entityManager->remove($dish);
        $entityManager->flush();
        
        return new JsonResponse(['message' => 'Dish deleted successfully']);
    }
}
