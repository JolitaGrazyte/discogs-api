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

    public function setType(string $type): SearchParameters
    {
        $this->type = $type;

        return $this;
    }

    public function setTitle(string $title): SearchParameters
    {
        $this->title = $title;

        return $this;
    }

    public function setLabel(string $label): SearchParameters
    {
        $this->label = $label;

        return $this;
    }

    public function setGenre(string $genre): SearchParameters
    {
        $this->genre = $genre;

        return $this;
    }

    public function setYear(int $year): SearchParameters
    {
        $this->year = $year;

        return $this;
    }

    public function setFormat(string $format): SearchParameters
    {
        $this->format = $format;

        return $this;
    }

    public function setCatalogNumber(string $catalogNumber): SearchParameters
    {
        $this->catno = $catalogNumber;

        return $this;
    }
}
