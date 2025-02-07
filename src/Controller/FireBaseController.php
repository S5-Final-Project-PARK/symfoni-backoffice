<?php

namespace App\Controller;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FireBaseController extends AbstractController
{
    #[Route('/firebase/test', name: 'firebase_test')]
    public function testFirebase(): JsonResponse
    {
        $firebase = (new Factory)->withServiceAccount(__DIR__ . '/../../config/firebase_credentials.json');
        $auth = $firebase->createAuth();

        return new JsonResponse(['message' => 'Firebase connected successfully!']);
    }
}
