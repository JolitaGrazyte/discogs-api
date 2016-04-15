<?php

namespace Jolita\DiscogsApiWrapper;

use GuzzleHttp\Client;

class DiscogsInventory extends DiscogsApi
{
    public function __construct($token, $userAgent)
    {
        parent::__construct($token, $userAgent);
    }

    public function getMyInventory(string $userName)
    {
        return $this->get("users/{$userName}/inventory", '', [], true);
    }

    public function deleteListing(string $listingId)
    {
        return $this->delete('marketplace/listings/', $listingId);
    }

    public function delete(string $resource, string $listingId)
    {
        return (new Client())
            ->delete(
                $this->url($this->path($resource, $listingId)),
                ['query' => ['token' => $this->token()]]
            );
    }
}
