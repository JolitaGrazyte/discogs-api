<?php

namespace Jolita\DiscogsApiWrapper;

use GuzzleHttp\Client;
use Jolita\DiscogsApiWrapper\Exceptions\DiscogsApiException;

class DiscogsApi
{
    protected $baseUrl = 'https://api.discogs.com';
    protected $token;
    protected $userAgent;

    public function __construct(string $token = null, string $userAgent = null)
    {
        $this->userAgent = $userAgent;
        $this->token = $token;
    }

    public function get(string $resource, string $id = '', array $query = [], bool $mustAuthenticate = false)
    {
        $content = (new Client())
            ->get(
                $this->url($this->path($resource, $id)),
                $this->parameters($query, $mustAuthenticate)
            )->getBody()
            ->getContents();

        return json_decode($content);
    }

    protected function parameters(array $query, bool $mustAuthenticate) : array
    {
        if ($mustAuthenticate) {
            $query = array_add($query, 'token', $this->token());
        }

        return  [
//            'stream' => true,
//            'headers' => ['User-Agent' => $this->userAgent ?: null],
            'query' => $query,
        ];
    }

    protected function token()
    {
        if(!is_null($this->token)){
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
