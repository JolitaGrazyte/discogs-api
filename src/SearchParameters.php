<?php

namespace Jolita\DiscogsApi;

use Illuminate\Support\Collection;

class SearchParameters
{
    /** @var string */
    protected $type;

    /** @var string */
    protected $title;

    /** @var string */
    protected $label;

    /** @var string */
    protected $genre;

    /** @var string */
    protected $year;

    /** @var string */
    protected $format;

    /** @var string */
    protected $catno;


    public static function make()
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

    public function settype(string $type)
    {
        $this->type = $type;

        return $this;
    }

    public function settitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    public function setLabel(string $label)
    {
        $this->label = $label;

        return $this;
    }

    public function setGenre(string $genre)
    {
        $this->genre = $genre;

        return $this;
    }

    public function setYear(string  $year)
    {
        $this->year = $year;

        return $this;
    }

    public function setFormat(string $format)
    {
        $this->format = $format;

        return $this;
    }

    public function setCatalogNumber(string $catalogNumber)
    {
        $this->catno = $catalogNumber;

        return $this;
    }
}
