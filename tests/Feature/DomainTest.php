<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Hunter\Facades\Hunter;

it('searches a domain', function () {
    Http::fake([
        'api.hunter.io/v2/domain-search*' => Http::response(['data' => ['domain' => 'example.com']], 200),
    ]);

    expect(Hunter::domainSearch('example.com', 10, 'personal'))->toBe(['data' => ['domain' => 'example.com']]);

    Http::assertSent(fn (Request $request) => $request['domain'] === 'example.com'
        && $request['limit'] === 10
        && $request['type'] === 'personal'
        && str_contains($request->url(), 'api_key=fake-api-key'));
});

it('throws when domain is empty for domain search', function () {
    expect(fn () => Hunter::domainSearch(''))->toThrow(InvalidArgumentException::class);
});

it('counts emails for a domain', function () {
    Http::fake([
        'api.hunter.io/v2/email-count*' => Http::response(['data' => ['total' => 42]], 200),
    ]);

    expect(Hunter::emailCount('example.com', 'personal'))->toBe(['data' => ['total' => 42]]);

    Http::assertSent(fn (Request $request) => $request['domain'] === 'example.com'
        && $request['type'] === 'personal');
});

it('throws when domain is empty for email count', function () {
    expect(fn () => Hunter::emailCount(''))->toThrow(InvalidArgumentException::class);
});
