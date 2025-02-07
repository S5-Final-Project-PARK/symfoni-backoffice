<?php
namespace App\Service;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Firestore;
use Google\Cloud\Firestore\FirestoreClient;
use Kreait\Firebase\Exception\DatabaseException;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;
use GuzzleHttp\Client;

class FirebaseService
{
    private $auth;
    private string $projectId;
    private string $credentialsPath;
    private Client $httpClient;
    private string $accessToken;

    public function __construct($firebaseCredentialsPath)
    {
        $factory = (new Factory)->withServiceAccount($firebaseCredentialsPath);
        $this->auth = $factory->createAuth();
        
        $this->credentialsPath = $firebaseCredentialsPath; // Update path
        $this->httpClient = new Client();
        $this->projectId = json_decode(file_get_contents($this->credentialsPath), true)['project_id'];

        // Authenticate and get an OAuth token
        $this->accessToken = $this->authenticate();
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
     * Authenticate using the service account and get an OAuth2 access token
     */
    private function authenticate(): string
    {
        $scopes = ['https://www.googleapis.com/auth/datastore'];

        $auth = new ServiceAccountCredentials($scopes, $this->credentialsPath);
        $auth->fetchAuthToken(HttpHandlerFactory::build());

        return $auth->getLastReceivedToken()['access_token'];
    }

    /**
     * Fetch a document from Firestore (REST API)
     */
    public function getDocument(string $collection, ?string $documentId): array
    {
        $url = ($documentId === null)
        ? "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}"
        : "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}/{$documentId}";

        try {
            $response = $this->httpClient->get($url, [
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                    'Content-Type' => 'application/json',
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return ['error' => 'Document not found'];
        }
    }

    /**
     * Send or update data in Firestore
     */
    public function setDocument(string $collection, string $documentId, array $data): array
    {
        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}/{$documentId}";
        
        try {
            $response = $this->httpClient->patch($url, [
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return ['error' => 'Failed to update document in Firestore', 'message' => $e->getMessage()];
        }
    }

    /**
     * Format Firestore data correctly (Firestore requires { "field_name": { "stringValue": "value" } })
     */
    private function formatFirestoreData(array $data): array
    {
        $formattedData = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                // Recursively handle nested arrays (for dishes)
                $formattedData[$key] = ['arrayValue' => ['values' => $this->formatFirestoreData($value)]];
            } else {
                $formattedData[$key] = ['stringValue' => (string) $value];
            }
        }

        return $formattedData;
    }
}
