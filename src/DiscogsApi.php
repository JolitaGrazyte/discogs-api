<?php

namespace Jolita\DiscogsApiWrapper;

class DiscogsApi
{
    protected $resources;
    protected $config;

    public function __construct()
    {
        $this->resources = require(__DIR__.'../../config/laravel-discogs-api-resources.php');
        $this->config = require(__DIR__.'../../config/laravel-discogs-api-config.php');
    }

    /**
     * Friendly welcome
     *
     * @param string $phrase Phrase to return
     *
     * @return string Returns the phrase passed in
     */
    public function echoPhrase($phrase)
    {
        return $phrase;
    }
}
