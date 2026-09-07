<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Hunter\Facades\Hunter;

it('lists leads', function () {
    Http::fake([
        'api.hunter.io/v2/leads*' => Http::response(['data' => ['leads' => []]], 200),
    ]);

    expect(Hunter::listLeads(10, 0))->toBe(['data' => ['leads' => []]]);

    Http::assertSent(fn (Request $request) => $request['limit'] === 10 && $request['offset'] === 0);
});

it('gets a lead', function () {
    Http::fake([
        'api.hunter.io/v2/leads/123*' => Http::response(['data' => ['id' => 123]], 200),
    ]);

    expect(Hunter::getLead('123'))->toBe(['data' => ['id' => 123]]);
});

it('throws when id is empty for get lead', function () {
    expect(fn () => Hunter::getLead(''))->toThrow(InvalidArgumentException::class);
});

it('creates a lead', function () {
    Http::fake([
        'api.hunter.io/v2/leads*' => Http::response(['data' => ['id' => 123]], 200),
    ]);

    expect(Hunter::createLead('jane@example.com', 'Jane', 'Doe', 'Acme'))->toBe(['data' => ['id' => 123]]);

    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request->data()['email'] === 'jane@example.com'
        && $request->data()['company'] === 'Acme');
});

it('throws when email is empty for create lead', function () {
    expect(fn () => Hunter::createLead(''))->toThrow(InvalidArgumentException::class);
});

it('deletes a lead', function () {
    Http::fake([
        'api.hunter.io/v2/leads/123*' => Http::response([], 204),
    ]);

    expect(Hunter::deleteLead('123'))->toBe([]);

    Http::assertSent(fn (Request $request) => $request->method() === 'DELETE');
});

it('throws when id is empty for delete lead', function () {
    expect(fn () => Hunter::deleteLead(''))->toThrow(InvalidArgumentException::class);
});
