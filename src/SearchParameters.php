<?php

namespace Jolita\DiscogsApiWrapper;

use Illuminate\Support\Collection;

class SearchParameters
{
    protected $type;
    protected $title;
    protected $label;
    protected $genre;
    protected $year;
    protected $format;
    protected $catno;

    public function __construct()
    {
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

    public function type(string $type)
    {
        $this->type = $type;
        return $this;
    }

    public function title(string $title)
    {
        $this->title = $title;
        return $this;
    }

    public function label(string $label)
    {
        $this->label = $label;
        return $this;
    }

    public function genre(string $genre)
    {
        $this->genre = $genre;
        return $this;
    }

    public function year(string  $year)
    {
        $this->year = $year;
        return $this;
    }

    public function format(string $format)
    {
        $this->format = $format;
        return $this;
    }

    public function catno(string $catno)
    {
        $this->catno = $catno;
        return $this;
    }
}
