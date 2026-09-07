<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Hunter\Exceptions\HunterException;
use JeffersonGoncalves\Hunter\Facades\Hunter;

it('fetches account info', function () {
    Http::fake([
        'api.hunter.io/v2/account*' => Http::response(['data' => ['email' => 'me@example.com']], 200),
    ]);

    expect(Hunter::accountInfo())->toBe(['data' => ['email' => 'me@example.com']]);
});

it('throws a HunterException on a non-2xx response', function () {
    Http::fake([
        'api.hunter.io/v2/account*' => Http::response(['errors' => [['details' => 'Invalid API key.']]], 401),
    ]);

    expect(fn () => Hunter::accountInfo())
        ->toThrow(HunterException::class, 'Invalid API key.');
});
