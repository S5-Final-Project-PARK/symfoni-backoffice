<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\Dishes;
use App\Service\FirebaseService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class OrderController extends AbstractController
{
    private FirebaseService $firebaseService;
    private EntityManagerInterface $em;

    public function __construct(FirebaseService $firebaseService, EntityManagerInterface $em)
    {
        $this->firebaseService = $firebaseService;
        $this->em = $em;
    }

    #[Route("/orders/save", name: "save_order", methods: ['POST'])]
    public function saveOrder(Request $request): JsonResponse
    {
        // Parse the request body
        $data = json_decode($request->getContent(), true);

        // Validate the required fields
        if (!isset($data['date'], $data['dishes'], $data['email'])) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }

        // Create the Order entity
        $order = new Orders();
        // $order->setIdClient($data['idClient']);
        $order->setEmail($data['email']);
        $order->setDate(new \DateTime($data['date'])); // Ensure the date is in a valid format (ATOM)

        // Assuming there is only one dish in the 'dishes' array
        $dishName = $data['dishes']['name']; // Example: 'dish1'
        $dish = $this->em->getRepository(Dishes::class)->findOneBy(['name' => $dishName]);
        if (!$dish) {
            return new JsonResponse(['error' => 'Dish not found'], 404);
        }
        $unit = $data['dishes']['unit'];

        if (!is_numeric($unit) || (int)$unit < 1) {
            return new JsonResponse(['error' => 'Unit must be a valid quantity (positive number)'], 400);
        }

        $order->setDish($dish);
        $order->setUnit((int)$unit); // Assuming unit is an integer or string
        $order->setUnitPrice($dish->getPrice()); // Assuming unit_price is a string or number

        // Persist the order in the database
        $this->em->persist($order);
        $this->em->flush();

        // Format Firestore data for the order (with confirmation set to false)
        $firestoreData = [
            'confirmation' => ['booleanValue' => false], // Firestore requires explicit boolean type
            'date' => ['timestampValue' => (new \DateTime($data['date']))->format('Y-m-d\TH:i:s\Z')], // Convert to Firestore timestamp format
            'dishes' => [
                'arrayValue' => [
                    'values' => [
                        [
                            'mapValue' => [
                                'fields' => [
                                    'name' => ['stringValue' => $data['dishes']['name']],
                                    'unit' => ['integerValue' => (int)$data['dishes']['unit']],
                                    'unit_price' => ['doubleValue' => (float)$dish->getPrice()]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'email' => ['stringValue' => $data['email']]
        ];

        // Save to Firestore
        $response = $this->firebaseService->setDocument('orders', (string) $order->getId(), $firestoreData);

        return new JsonResponse(['order' => $order, 'firestore_response' => $response], 201);
    }

    #[Route("/orders/list", name: "list_orders", methods: ['GET'])]
    public function getOrders(): JsonResponse
    {
        // Retrieve all orders
        $orders = $this->em->getRepository(Orders::class)->findAll();

        return $this->json($orders, 200, [], [
            "groups" => ["order.show"]
        ]);
    }

    #[Route("/orders/update/{id}", name: "update_order_confirmation", methods: ['POST'])]
    public function updateOrderConfirmation(int $id, Request $request): JsonResponse
    {
        // Find the order by ID
        $order = $this->em->getRepository(Orders::class)->find($id);
        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], 404);
        }

        // Update confirmation in the database
        $order->setConfirmation(true);
        $this->em->flush();

        // Update the confirmation status in Firestore
        $firestoreResponse = $this->firebaseService->setDocument('orders', (string) $id, [
            'confirmation' => true,
            'date' => $order->getDate()->format(\DateTime::ATOM),
            'dishes' => [
                [
                    'name' => $order->getDish()->getName(),
                    'unit' => $order->getUnit(),
                    'unit_price' => $order->getUnitPrice(),
                ]
            ],
            'email' => $order->getEmail()
        ]);

        return new JsonResponse([
            'message' => 'Order confirmation updated successfully',
            'firestore_response' => $firestoreResponse
        ], 200);
    }
}
