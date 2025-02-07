<?php
namespace App\Service;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Firestore;
use Kreait\Firebase\Exception\DatabaseException;

class FirebaseService
{
    private $auth;
    private Firestore $firestore;

    public function __construct(string $firebaseCredentialsPath)
    {
        $factory = (new Factory)->withServiceAccount($firebaseCredentialsPath);
        $this->auth = $factory->createAuth();
        $this->firestore = $factory->createFirestore();
    }

    public function verifyToken(string $idToken): ?Auth\UserRecord
    {
        try {
            return $this->auth->verifyIdToken($idToken);
        } catch (\Kreait\Firebase\Exception\AuthException $e) {
            return null;
        }
    }

    /**
     * Add or Update a Document in Firestore
     *
     * @param string $collection Name of the Firestore collection
     * @param string|null $documentId (Optional) If provided, updates the document; otherwise, creates a new one
     * @param array $data Data to store in Firestore
     * @return array The document ID and message
     */
    public function saveDocument(string $collection, ?string $documentId, array $data): array
    {
        try {
            $firestore = $this->firestore->database();
            $docRef = $documentId ? $firestore->collection($collection)->document($documentId) 
                                  : $firestore->collection($collection)->add($data);

            if ($documentId) {
                $docRef->set($data, ['merge' => true]); // Merging keeps existing fields
                return ['documentId' => $documentId, 'message' => 'Document updated successfully'];
            }

            return ['documentId' => $docRef->id(), 'message' => 'Document created successfully'];
        } catch (DatabaseException $e) {
            return ['error' => 'Failed to save document: ' . $e->getMessage()];
        }
    }
}
