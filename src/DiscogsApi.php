<?php

namespace Jolita\DiscogsApi;

use Exception;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\GuzzleException;
use Jolita\DiscogsApi\Exceptions\DiscogsApiException;
use JsonException;

class DiscogsApi
{
    protected string $baseUrl = 'https://api.discogs.com';
    protected Client $client;
    protected string $token;
    protected string $userAgent;

    public function __construct(Client $client, string $token = '', string $userAgent = '')
    {
        $this->client = $client;
        $this->token = $token;
        $this->userAgent = $userAgent;
    }

    /**
     * @throws DiscogsApiException
     * @throws GuzzleException
     */
    public function artist(string $id): ResponseInterface
    {
        return $this->get('artists', $id);
    }

    /**
     * @throws GuzzleException
     * @throws DiscogsApiException
     */
    public function artistReleases(string $artistId): ResponseInterface
    {
        return $this->get("artists/{$artistId}/releases");
    }

    /**
     * @throws GuzzleException
     * @throws DiscogsApiException
     */
    public function label(string $id): ResponseInterface
    {
        return $this->get('labels', $id);
    }

    /**
     * @throws GuzzleException
     * @throws DiscogsApiException
     */
    public function labelReleases(string $labelId): ResponseInterface
    {
        return $this->get("labels/{$labelId}/releases");
    }

    /**
     * @throws DiscogsApiException
     * @throws GuzzleException
     */
    public function release(string $id): ResponseInterface
    {
        return $this->getAuthenticated('releases', $id);
    }

    /**
     * @throws DiscogsApiException
     * @throws GuzzleException
     */
    public function masterRelease(string $id): ResponseInterface
    {
        return $this->get('masters', $id);
    }

    /**
     * @throws DiscogsApiException
     * @throws GuzzleException
     */
    public function userCollection(string $userName): ResponseInterface
    {
        return $this->get("/users/{$userName}/collection/folders");
    }

    /**
     * @throws GuzzleException
     * @throws DiscogsApiException
     */
    public function getMarketplaceListing(string $id): ResponseInterface
    {
        return $this->get("/marketplace/listings/{$id}");
    }

    /**
     * @throws GuzzleException
     * @throws DiscogsApiException
     */
    public function deleteListing(string $listingId): ResponseInterface
    {
        return $this->delete('marketplace/listings/', $listingId);
    }

    /**
     * @throws DiscogsApiException
     * @throws GuzzleException
     */
    public function orderWithId(string $id): ResponseInterface
    {
        return $this->getAuthenticated("marketplace/orders/{$id}");
    }

    /**
     * @throws GuzzleException
     * @throws DiscogsApiException
     */
    public function orderMessages(string $orderId): ResponseInterface
    {
        return $this->getAuthenticated("marketplace/orders/{$orderId}/messages");
    }

    /**
     * @throws DiscogsApiException
     * @throws GuzzleException
     */
    public function getMyOrders(int $page = null, int $perPage = null, string $status = null, string $sort = null, string $sortOrder = null): ResponseInterface
    {
        $query = [
            'page' => $page ?? 1,
            'per_page' => $perPage ?? 50,
            'status' => $status ?? 'All',
            'sort' => $sort ?? 'id',
            'sort_order' => $sortOrder ?? 'desc',
        ];

        return $this->getAuthenticated('marketplace/orders', '', $query);
    }

    /**
     * @throws DiscogsApiException
     * @throws GuzzleException
     */
    public function changeOrderStatus(string $orderId, string $status): ResponseInterface
    {
        return $this->changeOrder($orderId, 'status', $status);
    }

    /**
     * @throws GuzzleException
     * @throws DiscogsApiException
     */
    public function addShipping($orderId, string $shipping): ResponseInterface
    {
        return $this->changeOrder($orderId, 'shipping', $shipping);
    }

    /**
     * @throws DiscogsApiException
     * @throws GuzzleException
     */
    public function search(string $keyword, SearchParameters $searchParameters = null): ResponseInterface
    {
        $query = [
            'q' => $keyword,
        ];

        if (!is_null($searchParameters)) {
            $query = collect($query)->merge($searchParameters->get())->toArray();
        }

        return $this->get('database/search', '', $query, true);
    }

    /**
     * @throws DiscogsApiException
     * @throws GuzzleException
     */
    public function getAuthenticated(string $resource, string $id = '', array $query = []): ResponseInterface
    {
        return $this->get($resource, $id, $query, true);
    }

    /**
     * @throws GuzzleException
     * @throws DiscogsApiException
     */
    public function get(string $resource, string $id = '', array $query = [], bool $mustAuthenticate = false): ResponseInterface
    {
        return $this->client
            ->get(
                $this->url($this->path($resource, $id)),
                $this->parameters($query, $mustAuthenticate)
            );
    }

    /**
     * @throws DiscogsApiException
     * @throws GuzzleException
     */
    protected function changeOrder(string $orderId, string $key, string $value): ResponseInterface
    {
        $resource = 'marketplace/orders/';

        return $this->client
            ->post($this->url($this->path($resource, $orderId)), ['token' => $this->token()]
            );
    }

    /**
     * @throws DiscogsApiException
     * @throws GuzzleException
     */
    protected function delete(string $resource, string $listingId): ResponseInterface
    {
        return $this->client
            ->delete(
                $this->url($this->path($resource, $listingId)),
                ['query' => ['token' => $this->token()]]
            );
    }

    /**
     * @throws DiscogsApiException
     */
    public function requestInventoryExport(): ?ResponseInterface
    {
        return $this->post('inventory/export');
    }

    /**
     * @throws GuzzleException
     * @throws DiscogsApiException
     * @throws JsonException
     */
    public function getInventoryExports(int $page = 1, int $perPage = 100): ResponseInterface
    {
        return $this->getAuthenticated('inventory/export', '', ['page' => $page, 'per_page' => $perPage]);
    }

    /**
     * @throws DiscogsApiException
     * @throws Exception
     */
    public function post(string $resource, string $id = '', array $query = [], bool $mustAuthenticate = true): ResponseInterface
    {
        try {
            return $this->client
                ->post(
                    $this->url($this->path($resource, $id)),
                    $this->parameters($query, $mustAuthenticate)
                );

        } catch (GuzzleException $exception) {
            throw new \RuntimeException($exception->getMessage());
        }
    }

    /**
     * @throws DiscogsApiException
     */
    protected function parameters(array $query, bool $mustAuthenticate): array
    {
        if ($mustAuthenticate) {
            $query['token'] = $this->token();
        }

        return [
            'stream' => true,
            'headers' => ['User-Agent' => $this->userAgent ?: 'myAgent'],
            'query' => $query,
        ];
    }

    protected function token(): string
    {
        if (!is_null($this->token)) {
            return $this->token;
        }

        throw DiscogsApiException::tokenRequiredException();
    }

    protected function url(string $path): string
    {
        return "{$this->baseUrl}/{$path}";
    }

    protected function path(string $resource, string $id = ''): string
    {
        if (empty($id)) {
            return $resource;
        }

        return "{$resource}/{$id}";
    }
}
