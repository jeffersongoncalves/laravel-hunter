<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Hunter\Facades\Hunter;

it('lists leads lists', function () {
    Http::fake([
        'api.hunter.io/v2/leads_lists*' => Http::response(['data' => ['leads_lists' => []]], 200),
    ]);

    expect(Hunter::listLeadsLists(10, 0))->toBe(['data' => ['leads_lists' => []]]);

    Http::assertSent(fn (Request $request) => $request['limit'] === 10 && $request['offset'] === 0);
});

it('gets a leads list', function () {
    Http::fake([
        'api.hunter.io/v2/leads_lists/123*' => Http::response(['data' => ['id' => 123]], 200),
    ]);

    expect(Hunter::getLeadsList('123'))->toBe(['data' => ['id' => 123]]);
});

it('throws when id is empty for get leads list', function () {
    expect(fn () => Hunter::getLeadsList(''))->toThrow(InvalidArgumentException::class);
});
