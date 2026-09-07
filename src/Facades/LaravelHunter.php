<?php

namespace Jeffersongoncalves\LaravelHunter\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jeffersongoncalves\LaravelHunter\LaravelHunter
 */
class LaravelHunter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-hunter';
    }
}
