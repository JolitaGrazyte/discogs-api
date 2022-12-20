<?php

namespace Jolita\DiscogsApi\Exceptions;

use Exception;

class DiscogsApiException extends Exception
{
    public static function tokenRequiredException(): self
    {
        return new static('This endpoint requires authentication. Discogs token is required.');
    }

    public static function userAgentRequiredException(): self
    {
        return new static('To define userAgent is required.');
    }
}
