<?php

namespace Jolita\DiscogsApi\Entities;

class DiscogsResponse
{
    public int $id;
    public array $images;
    public array $artist;
    public array $labels;
    public array $formats;
    public string $title;

}