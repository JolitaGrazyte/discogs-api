<?php

namespace Jolita\DiscogsApiWrapper;


class DiscogsSearch extends DiscogsApi
{
    public function __construct($token, $userAgent)
    {
        parent::__construct($token, $userAgent);
    }

    public function search(string $keyword, SearchParameters $searchParameters = null)
    {
        $query = [
            'q' => $keyword,
        ];

        if(!is_null($searchParameters)) {
            $query = collect($query)->merge($searchParameters->get())->toArray();
        }

        return $this->get('database/search', '', $query, true);
    }

}
