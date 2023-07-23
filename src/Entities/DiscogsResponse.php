<?php

namespace Jolita\DiscogsApi\Entities;

class DiscogsRelease
{
    public int $id;
    public array $images;
    public array $artist;
    public string $title;
    public array $labels;
    public array $formats;
    public array $genres;
    public array $styles;

}