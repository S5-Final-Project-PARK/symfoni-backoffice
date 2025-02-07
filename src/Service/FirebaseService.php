<?php
namespace App\Service;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Firestore;
use Google\Cloud\Firestore\FirestoreClient;
use Kreait\Firebase\Exception\DatabaseException;

class FirebaseService
{
    private $auth;
    private Firestore $firestore;

    public function __construct(string $firebaseCredentialsPath)
    {
        $factory = (new Factory)->withServiceAccount($firebaseCredentialsPath);
        $this->auth = $factory->createAuth();
        $this->firestore = new FirestoreClient([
            'keyFilePath' => $firebaseCredentialsPath
        ]);
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

    /**
     * Fetch a Document from Firestore
     *
     * @param string $collection Name of the Firestore collection
     * @param string $documentId ID of the document
     * @return array Document data or error message
     */
    public function getDocument(string $collection, string $documentId): array
    {
        try {
            $docRef = $this->firestore->collection($collection)->document($documentId);
            $snapshot = $docRef->snapshot();

            if (!$snapshot->exists()) {
                return ['error' => 'Document not found'];
            }

            return ['documentId' => $documentId, 'data' => $snapshot->data()];
        } catch (DatabaseException $e) {
            return ['error' => 'Failed to fetch document: ' . $e->getMessage()];
        }
    }

    /**
     * Fetch All Documents from a Firestore Collection
     *
     * @param string $collection Name of the Firestore collection
     * @return array List of documents
     */
    public function getAllDocuments(string $collection): array
    {
        try {
            $documents = $this->firestore->collection($collection)->documents();
            $result = [];

            foreach ($documents as $document) {
                if ($document->exists()) {
                    $result[] = ['documentId' => $document->id(), 'data' => $document->data()];
                }
            }

            return $result;
        } catch (DatabaseException $e) {
            return ['error' => 'Failed to fetch documents: ' . $e->getMessage()];
        }
    }

    /**
     * Delete a Document from Firestore
     *
     * @param string $collection Name of the Firestore collection
     * @param string $documentId ID of the document to delete
     * @return array Response message
     */
    public function deleteDocument(string $collection, string $documentId): array
    {
        try {
            $this->firestore->collection($collection)->document($documentId)->delete();
            return ['documentId' => $documentId, 'message' => 'Document deleted successfully'];
        } catch (DatabaseException $e) {
            return ['error' => 'Failed to delete document: ' . $e->getMessage()];
        }
    }
}
