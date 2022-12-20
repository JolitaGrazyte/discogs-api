<?php

namespace Jolita\DiscogsApi;

use GuzzleHttp\Client;
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws JsonException
     */
    public function artist(string $id)
    {
        return $this->get('artists', $id);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws DiscogsApiException
     * @throws JsonException
     */
    public function artistReleases(string $artistId)
    {
        return $this->get("artists/{$artistId}/releases");
    }

    public function label(string $id)
    {
        return $this->get('labels', $id);
    }

    public function labelReleases(string $labelId)
    {
        return $this->get("labels/{$labelId}/releases");
    }

    public function release(string $id)
    {
        return $this->get('releases', $id);
    }

    public function masterRelease(string $id)
    {
        return $this->get('masters', $id);
    }

    public function userCollection(string $userName)
    {
        return $this->get("/users/{$userName}/collection/folders");
    }

    public function getMarketplaceListing(string $id)
    {
        return $this->get("/marketplace/listings/{$id}");
    }

    public function getMyInventory(string $userName)
    {
        return $this->getAuthenticated("users/{$userName}/inventory");
//        return $this->get("users/{$userName}/inventory", '', [], true);
    }

    public function deleteListing(string $listingId)
    {
        return $this->delete('marketplace/listings/', $listingId);
    }

    public function orderWithId(string $id)
    {
//        return $this->get("marketplace/orders/{$id}", '', [], true);
        return $this->getAuthenticated("marketplace/orders/{$id}");
    }

    public function orderMessages(string $orderId)
    {
//        return $this->get("marketplace/orders/{$orderId}/messages", '', [], true);
        return $this->getAuthenticated("marketplace/orders/{$orderId}/messages");
    }

    public function getMyOrders(int $page = null, int $perPage = null, string $status = null, string $sort = null, string $sortOrder = null)
    {
        $query = [
            'page' => $page ?? 1,
            'per_page' => $perPage ?? 50,
            'status' => $status ?? 'All',
            'sort' => $sort ?? 'id',
            'sort_order' => $sortOrder ?? 'desc',
        ];

//        return $this->get('marketplace/orders', '', $query, true);
        return $this->getAuthenticated('marketplace/orders', '', $query);
    }

    public function changeOrderStatus(string $orderId, string $status)
    {
        return $this->changeOrder($orderId, 'status', $status);
    }

    public function addShipping($orderId, string $shipping)
    {
        return $this->changeOrder($orderId, 'shipping', $shipping);
    }

    public function search(string $keyword, SearchParameters $searchParameters = null)
    {
        $query = [
            'q' => $keyword,
        ];

        if (!is_null($searchParameters)) {
            $query = collect($query)->merge($searchParameters->get())->toArray();
        }

        return $this->get('database/search', '', $query, true);
    }

    protected function getAuthenticated(string $resource, string $id = '', array $query = [])
    {
        return $this->get($resource, $id, $query, true);
    }

    public function get(string $resource, string $id = '', array $query = [], bool $mustAuthenticate = false)
    {
        $content = $this->client
            ->get(
                $this->url($this->path($resource, $id)),
                $this->parameters($query, $mustAuthenticate)
            )->getBody()
            ->getContents();

        return json_decode($content);
    }

    /**
     * @throws DiscogsApiException
     * @throws GuzzleException
     */
    protected function changeOrder(string $orderId, string $key, string $value)
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
    protected function delete(string $resource, string $listingId)
    {
        return $this->client
            ->delete(
                $this->url($this->path($resource, $listingId)),
                ['query' => ['token' => $this->token()]]
            );
    }

    /**
     * @throws GuzzleException
     * @throws DiscogsApiException
     */
    public function requestInventoryExport()
    {
        return $this->post('inventory/export');
    }

    /**
     * @throws GuzzleException
     * @throws DiscogsApiException
     * @throws JsonException
     */
    public function getInventoryExports()
    {
        return $this->getAuthenticated('inventory/export');
    }

    /**
     * @throws GuzzleException
     * @throws DiscogsApiException
     * @throws JsonException
     */
    public function post(string $resource, string $id = '', array $query = [], bool $mustAuthenticate = true)
    {
        try {
            return $this->client
                ->post(
                    $this->url($this->path($resource, $id)),
                    $this->parameters($query, $mustAuthenticate)
                );

        } catch (GuzzleException $exception) {
            print $exception;
        }
    }

    /**
     * @throws DiscogsApiException
     */
    protected function parameters(array $query, bool $mustAuthenticate) : array
    {
        if ($mustAuthenticate) {
            $query['token'] = $this->token();
        }

        return  [
            'stream' => true,
            'headers' => ['User-Agent' => $this->userAgent ?: 'myAgent'],
            'query' => $query,
        ];
    }

    protected function token()
    {
        if (!is_null($this->token)) {
            return $this->token;
        }

        throw DiscogsApiException::tokenRequiredException();
    }

    protected function url(string $path) : string
    {
        return "{$this->baseUrl}/{$path}";
    }

    protected function path(string $resource, string $id = '')
    {
        if (empty($id)) {
            return $resource;
        }

        return "{$resource}/{$id}";
    }
}
