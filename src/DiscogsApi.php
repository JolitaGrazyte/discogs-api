<?php

namespace Jolita\DiscogsApiWrapper;

use GuzzleHttp\Client;
use Jolita\DiscogsApiWrapper\Exceptions\DiscogsApiException;

class DiscogsApi
{
    protected $baseUrl = 'https://api.discogs.com';
    protected $token;
    protected $userAgent;
    protected $client;

    public function __construct(Client $client, string $token = null, string $userAgent = null)
    {
        $this->userAgent = $userAgent;
        $this->token = $token;
        $this->client = $client;
    }

    public function artist(string $id)
    {
        return $this->get('artists', $id);
    }

    public function artistReleases(string $artistId)
    {
        $resource = "artists/{$artistId}/releases";

        return $this->get($resource);
    }

    public function label(string $id)
    {
        return $this->get('labels', $id);
    }

    public function labelReleases(string $labelId)
    {
        $resource = "labels/{$labelId}/releases";

        return $this->get($resource, '');
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
        return $this->get("users/{$userName}/inventory", '', [], true);
    }

    public function deleteListing(string $listingId)
    {
        return $this->delete('marketplace/listings/', $listingId);
    }

    public function orderWithId(string $id)
    {
        return $this->get("marketplace/orders/{$id}", '', [], true);
    }

    public function orderMessages(string $orderId)
    {
        return $this->get("marketplace/orders/{$orderId}/messages", '', [], true);
    }

    public function getMyOrders(int $page = null, int $per_page = null, string $status = null, string $sort = null, string $sort_order = null)
    {
        $query = [
            'page' => $page ?: 1,
            'per_page' => $per_page ?: 50,
            'status' => $status ?: 'All',
            'sort' => $sort ?: 'id',
            'sort_order' => $sort_order ?: 'desc',
        ];

        return $this->get('marketplace/orders', '', $query, true);
    }

    public function changeOrderStatus(string $orderId, string $status)
    {
        return $this->postChanges($orderId, 'status', $status);
    }

    public function addShipping($orderId, string $shipping)
    {
        return $this->postChanges($orderId, 'shipping', $shipping);
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

    protected function postChanges(string $orderId, string $key, string $value)
    {
        $resource = 'marketplace/orders/';

        return $this->client
            ->post($this->url($this->path($resource, $orderId)),
                ['query' => [
                    $key => $value,
                    'token' => $this->token(),
                ],
                ]
            );
    }

    protected function delete(string $resource, string $listingId)
    {
        return $this->client
            ->delete(
                $this->url($this->path($resource, $listingId)),
                ['query' => ['token' => $this->token()]]
            );
    }

    protected function parameters(array $query, bool $mustAuthenticate) : array
    {
        if ($mustAuthenticate) {
            $query = array_add($query, 'token', $this->token());
        }

        return  [
            'headers' => ['User-Agent' => $this->userAgent ?: null],
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
