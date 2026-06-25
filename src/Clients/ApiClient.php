<?php

namespace UsmanZahid\N8n\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use UsmanZahid\N8n\Entities\Entity;
use UsmanZahid\N8n\Helpers\RequestHelper;
use UsmanZahid\N8n\Response\N8nResponse;

/**
 * Base client for interacting with the N8N API.
 * Returns decoded arrays; no user-facing response handling.
 */
class ApiClient {
    protected string $baseUrl;
    protected string $apiPathPrefix = '/api/v1';
    protected string $apiKey;
    protected Client $http;

    public function __construct(string $baseUrl, string $apiKey) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;

        $this->http = new Client([
            'headers' => [
                'X-N8N-API-KEY' => $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Send a request to the API and return the decoded JSON as an array.
     *
     * @param string $method HTTP method (GET, POST, PUT, PATCH, DELETE)
     * @param string $endpoint API endpoint starting with '/'
     * @param array $data Query parameters or JSON body
     * @return array Decoded JSON response or empty array on failure
     */
    protected function request(string $method, string $endpoint, array $data = []): array {
        $options = RequestHelper::buildOptions($method, $data);
        $url = $this->baseUrl . $this->apiPathPrefix . $endpoint;

        try {
            $response = $this->http->request($method, $url, $options);
            $body = (string) $response->getBody();
            $decoded = $body==='' ? null:(json_decode($body, true) ?? $body);

            return [
                'success' => true,
                'data' => $decoded,
                'message' => null,
                'code' => $response->getStatusCode(),
            ];
        } catch (GuzzleException $e) {
            return RequestHelper::handleException($e, true);
        }
    }

    // Convenience wrappers for HTTP methods
    protected function get(string $endpoint, array $query = []): array {
        return $this->request('GET', $endpoint, $query);
    }

    protected function post(string $endpoint, array $data = []): array {
        return $this->request('POST', $endpoint, $data);
    }

    protected function put(string $endpoint, array $data = []): array {
        return $this->request('PUT', $endpoint, $data);
    }

    protected function patch(string $endpoint, array $data = []): array {
        return $this->request('PATCH', $endpoint, $data);
    }

    protected function delete(string $endpoint): array {
        return $this->request('DELETE', $endpoint);
    }

    /**
     * Wrap a raw API response into an N8nResponse, optionally hydrating a single entity.
     *
     * @param array $raw
     * @param string|null $entityClass Fully-qualified entity class name
     * @return N8nResponse
     */
    protected function wrapEntity(array $raw, ?string $entityClass = null): N8nResponse {
        $success = $raw['success'] ?? false;
        $code    = $raw['code'] ?? ($success ? 200 : 500);
        $message = $raw['message'] ?? ($success ? 'Success' : 'Error');

        $data = $success && $entityClass && isset($raw['data'])
            ? new $entityClass($raw['data'])
            : ($success ? $raw['data'] : null);

        return new N8nResponse($success, $data, $message, $code);
    }

    /**
     * Wrap a raw API response whose data is a plain array of items into an
     * N8nResponse<EntityClass[]>. Use this for endpoints that return a bare
     * JSON array rather than a paginated envelope.
     *
     * @param array $raw
     * @param string $entityClass Fully-qualified entity class name
     * @return N8nResponse
     */
    protected function wrapArray(array $raw, string $entityClass): N8nResponse {
        $success = $raw['success'] ?? false;
        $code    = $raw['code'] ?? ($success ? 200 : 500);
        $message = $raw['message'] ?? ($success ? 'Success' : 'Error');

        if (!$success) {
            return new N8nResponse(false, null, $message, $code);
        }

        $data  = $raw['data'] ?? [];
        $items = is_array($data) ? array_map(fn($item) => new $entityClass($item), $data) : [];

        return new N8nResponse(true, $items, $message, $code);
    }

    /**
     * Extract a string ID from either a plain string or an Entity instance.
     * Reads the entity's $id property, which all addressable entities expose.
     *
     * @param string|Entity $subject
     * @return string
     */
    protected function resolveId(string|Entity $subject): string {
        return $subject instanceof Entity ? $subject->id : $subject;
    }

    /**
     * Normalize a payload that is either a raw array or an Entity instance.
     * Entities are serialized via toArray() before being sent to the API.
     *
     * @param array|Entity $payload
     * @return array
     */
    protected function resolvePayload(array|Entity $payload): array {
        return $payload instanceof Entity ? $payload->toArray() : $payload;
    }

}
