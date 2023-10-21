<?php

namespace Jolita\DiscogsApi;

use GuzzleHttp\Client;

class DiscogsApiClient
{
    protected Client $client;
    protected array $config;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}