<?php

namespace JeffersonGoncalves\Hunter\Facades;

use Illuminate\Support\Facades\Facade;
use JeffersonGoncalves\Hunter\HunterClient;

/**
 * @see HunterClient
 */
class Hunter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'hunter';
    }
}
