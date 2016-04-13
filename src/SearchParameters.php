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

    public function __construct(){}

    public function get() : Collection
    {
        $fields = [
            'type' => $this->type,
            'title' => $this->title,
            'label' => $this->label,
            'genre' => $this->genre,
            'year' => $this->year,
            'format' => $this->format,
            'catno' => $this->catno
        ];

        return collect($fields)->reject(function($value){
            return is_null($value);
        });
    }

    public function type(string $type)
    {
        return $this->type = $type;
    }

    public function title(string $title)
    {
        return $this->title = $title;
    }

    public function label(string $label)
    {
        return $this->label = $label;
    }

    public function genre(string $genre)
    {
        return $this->genre = $genre;
    }

    public function year(string  $year)
    {
        return $this->year = $year;
    }

    public function format(string $format)
    {
        return $this->format = $format;
    }

    public function catno(string $catno)
    {
        return $this->catno = $catno;
    }


}
