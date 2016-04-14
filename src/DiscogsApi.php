<?php

namespace Jolita\DiscogsApiWrapper;

use GuzzleHttp\Client;
use Jolita\DiscogsApiWrapper\Exceptions\DiscogsApiException;

class DiscogsApi
{
    protected $baseUrl;
    protected $token;
    protected $userAgent;

    public function __construct(string $token = '', string $userAgent = '')
    {
        $this->userAgent = $userAgent;
        $this->token = $token;
        $this->baseUrl = 'https://api.discogs.com';
    }

    public function artist(string $id)
    {
        return $this->get('artists', $id, [], false);
    }

    public function artistReleases(string $artistId)
    {
        $resource = "artists/{$artistId}/releases";

        return $this->get($resource, '', [], false);
    }

    public function label(string $id)
    {
        return $this->get('labels', $id, [], false);
    }

    public function labelReleases(string $labelId)
    {
        $resource = "labels/{$labelId}/releases";

        return $this->get($resource, '', [], false);
    }

    public function release(string $id)
    {
        return $this->get('releases', $id, [], false);
    }

    public function masterRelease(string $id)
    {
        return $this->get('masters', $id, [], false);
    }

    public function userCollection(string $userName)
    {
        return $this->get("/users/{$userName}/collection/folders", '', [], false);
    }

    public function getMarketplaceListing(string $id)
    {
        return $this->get("/marketplace/listings/{$id}", '', [], true);
    }
    public function getUsersInventory(string $userName)
    {
        $this->get("/users/$userName}/inventory", '', [], false);
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

    public function search($keyword, SearchParameters $searchParameters)
    {
        $query = [
            'q' => $keyword,
        ];

        $query = collect($query)->merge($searchParameters->get())->toArray();

        return $this->get('database/search', '', $query, true);
    }

    public function get(string $resource, string $id = '', array $query = [], bool $mustAuthenticate = false)
    {
        $content = (new Client())
            ->get(
                $this->url($this->path($resource, $id)),
                $this->parameters($query, $mustAuthenticate)
            )
            ->getBody()
            ->getContents();

        return json_decode($content);
    }

    protected function parameters(array $query, bool $mustAuthenticate) : array
    {
        if ($mustAuthenticate) {
            $query = array_add($query, 'token', $this->token());
        }

        return  [
            'stream' => true,
            'headers' => ['User-Agent' => $this->userAgent ?: null],
            'query' => $query,
        ];
    }

    protected function token()
    {
        $token = $this->token;

        if (empty($token)) {
            throw DiscogsApiException::tokenRequiredException();
        }

        return $token;
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
