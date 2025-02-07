<?php
// src/Service/FirebaseService.php
namespace App\Service;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

class FirebaseService
{
    private $auth;

    public function __construct(string $firebaseCredentialsPath)
    {
        $factory = (new Factory)->withServiceAccount($firebaseCredentialsPath);
        $this->auth = $factory->createAuth();
    }

    public function verifyToken(string $idToken): ?Auth\UserRecord
    {
        try {
            return $this->auth->verifyIdToken($idToken);
        } catch (\Kreait\Firebase\Exception\AuthException $e) {
            return null;
        }
    }
}
