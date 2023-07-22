<?php

namespace Jolita\DiscogsApi;

use Illuminate\Support\Collection;

class SearchParameters
{
    /** @var string */
    protected string $type;

    /** @var string */
    protected string $title;

    /** @var string */
    protected string $label;

    /** @var string */
    protected string $genre;

    /** @var int */
    protected int $year;

    /** @var string */
    protected string $format;

    /** @var string */
    protected string $catno;


    public static function make(): SearchParameters
    {
        return (new static());
    }

    public function get() : Collection
    {
        $fields = [
            'type' => $this->type,
            'title' => $this->title,
            'label' => $this->label,
            'genre' => $this->genre,
            'year' => $this->year,
            'format' => $this->format,
            'catno' => $this->catno,
        ];

        return collect($fields)->reject(function ($value) {
            return is_null($value);
        });
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function setFormat(string $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function setCatalogNumber(string $catalogNumber): static
    {
        $this->catno = $catalogNumber;

        return $this;
    }
}
