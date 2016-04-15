<?php

namespace Jolita\DiscogsApiWrapper;

class Discogs extends DiscogsApi
{
    public function __construct($token = null, $userAgent = null)
    {
        parent::__construct($token, $userAgent);
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
}
