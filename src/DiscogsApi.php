<?php

namespace Jolita\DiscogsApiWrapper;

use GuzzleHttp\Client;

class DiscogsApi
{
    protected $baseUrl;
    protected $token;
    protected $userAgent;

    public function __construct($token, $userAgent = null)
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

    public function orderWithId(string $id)
    {
        return $this->get('orders', $id, [], true);
    }

    public function myOrders()
    {
        return $this->get('orders', '', [], true);
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
        return  [
            'stream' => true,
            'headers' => ['User-Agent' => $this->userAgent ?: null],
            'query' =>
                $mustAuthenticate ?
                    array_add($query, 'token', $this->token) : $query
        ];
    }

    protected function url(string $path) : string
    {
        return "{$this->baseUrl}/{$path}";
    }

    /**
     * @param string $resource
     * @param string $id
     *
     * @return string
     */
    protected function path(string $resource, string $id = '')
    {
        if(empty($id))
        {
            return $resource;
        }
        return "{$resource}/{$id}";
    }

}
