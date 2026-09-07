<?php

namespace JeffersonGoncalves\Hunter;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use JeffersonGoncalves\Hunter\Exceptions\HunterException;

/**
 * Thin client for the Hunter.io v2 REST API
 * (https://api.hunter.io/v2). Every method returns the raw decoded JSON
 * response as an array and authenticates the request with the `api_key`
 * query parameter from `HUNTER_API_KEY`.
 */
class HunterClient
{
    /**
     * @return array<string, mixed>
     */
    public function domainSearch(string $domain, ?int $limit = null, ?string $type = null): array
    {
        $this->requireNotEmpty($domain, 'domain');

        return $this->get('/domain-search', $this->filter([
            'domain' => $domain,
            'limit' => $limit,
            'type' => $type,
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    public function emailCount(string $domain, ?string $type = null): array
    {
        $this->requireNotEmpty($domain, 'domain');

        return $this->get('/email-count', $this->filter([
            'domain' => $domain,
            'type' => $type,
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    public function findEmail(string $domain, string $firstName, string $lastName): array
    {
        $this->requireNotEmpty($domain, 'domain');
        $this->requireNotEmpty($firstName, 'first_name');
        $this->requireNotEmpty($lastName, 'last_name');

        return $this->get('/email-finder', [
            'domain' => $domain,
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function verifyEmail(string $email): array
    {
        $this->requireNotEmpty($email, 'email');

        return $this->get('/email-verifier', ['email' => $email]);
    }

    /**
     * @return array<string, mixed>
     */
    public function accountInfo(): array
    {
        return $this->get('/account');
    }

    /**
     * @return array<string, mixed>
     */
    public function listLeads(?int $limit = null, ?int $offset = null): array
    {
        return $this->get('/leads', $this->filter([
            'limit' => $limit,
            'offset' => $offset,
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    public function getLead(string $id): array
    {
        $this->requireNotEmpty($id, 'id');

        return $this->get("/leads/{$id}");
    }

    /**
     * @return array<string, mixed>
     */
    public function createLead(string $email, ?string $firstName = null, ?string $lastName = null, ?string $company = null): array
    {
        $this->requireNotEmpty($email, 'email');

        return $this->post('/leads', $this->filter([
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'company' => $company,
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    public function deleteLead(string $id): array
    {
        $this->requireNotEmpty($id, 'id');

        return $this->delete("/leads/{$id}");
    }

    /**
     * @return array<string, mixed>
     */
    public function listCampaigns(?int $limit = null, ?int $offset = null): array
    {
        return $this->get('/campaigns', $this->filter([
            'limit' => $limit,
            'offset' => $offset,
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    public function getCampaign(string $id): array
    {
        $this->requireNotEmpty($id, 'id');

        return $this->get("/campaigns/{$id}");
    }

    /**
     * @return array<string, mixed>
     */
    public function startCampaign(string $id): array
    {
        $this->requireNotEmpty($id, 'id');

        return $this->post("/campaigns/{$id}/start");
    }

    /**
     * @return array<string, mixed>
     */
    public function pauseCampaign(string $id): array
    {
        $this->requireNotEmpty($id, 'id');

        return $this->post("/campaigns/{$id}/pause");
    }

    /**
     * @return array<string, mixed>
     */
    public function listLeadsLists(?int $limit = null, ?int $offset = null): array
    {
        return $this->get('/leads_lists', $this->filter([
            'limit' => $limit,
            'offset' => $offset,
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    public function getLeadsList(string $id): array
    {
        $this->requireNotEmpty($id, 'id');

        return $this->get("/leads_lists/{$id}");
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     *
     * @throws HunterException
     */
    private function get(string $uri, array $query = []): array
    {
        return $this->handle($this->http()->get($uri, $query));
    }

    /**
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     *
     * @throws HunterException
     */
    private function post(string $uri, array $body = []): array
    {
        return $this->handle($this->http()->post($uri, $body));
    }

    /**
     * @return array<string, mixed>
     *
     * @throws HunterException
     */
    private function delete(string $uri): array
    {
        return $this->handle($this->http()->delete($uri));
    }

    private function http(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl())
            ->acceptJson()
            ->withQueryParameters(['api_key' => $this->apiKey()]);
    }

    /**
     * @return array<string, mixed>
     *
     * @throws HunterException
     */
    private function handle(Response $response): array
    {
        if ($response->failed()) {
            throw new HunterException($this->errorMessage($response), $response->status());
        }

        $data = $response->json();

        return is_array($data) ? $data : [];
    }

    private function errorMessage(Response $response): string
    {
        $data = $response->json();

        if (is_array($data) && is_string($data['errors'][0]['details'] ?? null)) {
            return $data['errors'][0]['details'];
        }

        return $response->body() !== ''
            ? $response->body()
            : "Hunter.io API request failed with status {$response->status()}.";
    }

    private function requireNotEmpty(string $value, string $field): void
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException("The [{$field}] parameter is required.");
        }
    }

    /**
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    private function filter(array $params): array
    {
        return array_filter($params, fn (mixed $value): bool => $value !== null);
    }

    private function apiKey(): string
    {
        return (string) config('hunter.api_key');
    }

    private function baseUrl(): string
    {
        return (string) config('hunter.base_url', 'https://api.hunter.io/v2');
    }
}
