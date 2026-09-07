<?php

namespace JeffersonGoncalves\Hunter\Exceptions;

use RuntimeException;

/**
 * Raised when the Hunter.io API answers a request with a non-2xx HTTP
 * status. Carries the response's error message (`errors[0].details`, falling
 * back to the raw body) and the HTTP status code.
 */
class HunterException extends RuntimeException
{
    public function __construct(string $message, public readonly int $statusCode)
    {
        parent::__construct($message, $statusCode);
    }
}
