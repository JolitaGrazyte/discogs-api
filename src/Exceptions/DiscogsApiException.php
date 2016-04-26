<?php

namespace Jolita\DiscogsApi\Exceptions;

use Exception;

class DiscogsApiException extends Exception
{
    public static function tokenRequiredException()
    {
        return new static('This endpoint requires authentication. Discogs token is required.');
    }
}
