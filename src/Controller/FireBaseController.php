<?php

namespace App\Controller;

use Kreait\Firebase\Factory;
use App\Service\FirebaseService;
use Kreait\Firebase\Auth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FireBaseController extends AbstractController
{
    private $auth;
    private $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        // Initialize Firebase Auth
        $firebase = (new Factory)->withServiceAccount(__DIR__ . '/../../config/firebase_credentials.json'); // Path to your Firebase credentials file
        $this->auth = $firebase->createAuth();
        $this->firebaseService = $firebaseService;
    }

    #[Route('/firebase/test', name: 'firebase_test')]
    public function testFirebase(): JsonResponse
    {
        $firebase = (new Factory)->withServiceAccount(__DIR__ . '/../../config/firebase_credentials.json');
        $auth = $firebase->createAuth();

        return new JsonResponse(['message' => 'Firebase connected successfully!']);
    }


    #[Route("/firebase/login", name:"firebase_login", methods:['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (isset($data['email'], $data['password'])) {
            try {
                // Here we verify the user credentials (email/password) using Firebase Authentication
                $signInResult = $this->auth->signInWithEmailAndPassword($data['email'], $data['password']);
                $idToken = $signInResult->idToken(); // Get the ID token

                // Return the token as a response
                return new JsonResponse([
                    'message' => 'Login successful',
                    'idToken' => $idToken,
                ],200);
            } catch (\Kreait\Firebase\Exception\Auth\InvalidPassword $e) {
                return new JsonResponse(['error' => 'Invalid password'], Response::HTTP_UNAUTHORIZED);
            } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
                return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            } catch (\Kreait\Firebase\Exception\AuthException $e) {
                return new JsonResponse(['error' => 'Authentification Error'], Response::HTTP_UNAUTHORIZED);
            }
        }

        return new JsonResponse(['message' => 'Email and password are required'], Response::HTTP_BAD_REQUEST);
    }

    #[Route("/firebase/verify", name:"firebase_verify", methods:['POST'])]
    public function verifyToken(Request $request): JsonResponse
    {
        $token = $request->headers->get('Authorization');

        if (empty($token)) {
            return new JsonResponse(['error' => 'Authorization header missing'], 400);
        }

        // Remove the "Bearer " prefix from the token
        $idToken = str_replace('Bearer ', '', $token);

        $user = $this->firebaseService->verifyToken($idToken);

        if ($user === null) {
            return new JsonResponse(['error' => 'Invalid or expired token'], 401);
        }

        // Token is valid, and you can now use user data
        return new JsonResponse(['message' => 'Token is valid', 'user' => $user->uid]);
    }

    #[Route("/firebase/save", name: "firebase_save", methods: ['POST'])]
    public function setDocument(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Ensure the required fields exist
        if (!isset($data['collection'], $data['documentId'], $data['fields'])) {
            return new JsonResponse(['error' => 'Missing collection, documentId, or fields'], 400);
        }

        $response = $this->firebaseService->setDocument($data['collection'], $data['documentId'], $data['fields']);
        return new JsonResponse($response, 200);
    }

    #[Route("/firebase/get", name: "firebase_get", methods: ['POST'])]
    public function getDocument(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Ensure the required fields exist
        if (!isset($data['collection'])) {
            return new JsonResponse(['error' => 'Missing collection or documentId'], 400);
        }

        $collection = $data['collection'];
        $documentId = $data['documentId'] ?? null;

        $response = $this->firebaseService->getDocument($collection, $documentId);
        return new JsonResponse($response, 200);
    }


}
