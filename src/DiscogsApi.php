<?php

namespace Jolita\DiscogsApiWrapper;

use GuzzleHttp\Client;

class DiscogsApi
{
    protected $resources;
    protected $config;
    protected $baseUrl;

    public function __construct()
    {
        $this->resources = require(__DIR__.'../../config/discogs-resources.php');
        $this->config = require(__DIR__.'../../config/discogs-api.php');
        $this->baseUrl = 'https://api.discogs.com';
    }

    public function get(string $resource, string $id = '', array $query = [])
    {
        $path = $this->path($id, $resource);

        $content = (new Client())
            ->get(
                $this->url($path),
                $this->parameters($query)
            )
            ->getBody()
            ->getContents();

        return json_decode($content);
    }

    protected function path(string $id, string $resource) : string
    {
        $resourcePath =  $this->resources[$resource];

        return !empty($id) ? str_replace('{id}', $id, $resourcePath) : $resourcePath;
    }

    protected function parameters(array $query) : array
    {
        $token = $this->config['token'];

        return [
            'stream' => true,
            'query' => array_add($query, 'token', $token)
        ];
    }

    protected function url(string $path) : string
    {
        return "{$this->baseUrl}/{$path}";
    }
}
