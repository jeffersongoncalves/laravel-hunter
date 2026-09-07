<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Hunter\Facades\Hunter;

it('lists campaigns', function () {
    Http::fake([
        'api.hunter.io/v2/campaigns*' => Http::response(['data' => ['campaigns' => []]], 200),
    ]);

    expect(Hunter::listCampaigns(10, 0))->toBe(['data' => ['campaigns' => []]]);

    Http::assertSent(fn (Request $request) => $request['limit'] === 10 && $request['offset'] === 0);
});

it('gets a campaign', function () {
    Http::fake([
        'api.hunter.io/v2/campaigns/123*' => Http::response(['data' => ['id' => 123]], 200),
    ]);

    expect(Hunter::getCampaign('123'))->toBe(['data' => ['id' => 123]]);
});

it('throws when id is empty for get campaign', function () {
    expect(fn () => Hunter::getCampaign(''))->toThrow(InvalidArgumentException::class);
});

it('starts a campaign', function () {
    Http::fake([
        'api.hunter.io/v2/campaigns/123/start*' => Http::response(['data' => ['id' => 123]], 200),
    ]);

    expect(Hunter::startCampaign('123'))->toBe(['data' => ['id' => 123]]);

    Http::assertSent(fn (Request $request) => $request->method() === 'POST');
});

it('throws when id is empty for start campaign', function () {
    expect(fn () => Hunter::startCampaign(''))->toThrow(InvalidArgumentException::class);
});

it('pauses a campaign', function () {
    Http::fake([
        'api.hunter.io/v2/campaigns/123/pause*' => Http::response(['data' => ['id' => 123]], 200),
    ]);

    expect(Hunter::pauseCampaign('123'))->toBe(['data' => ['id' => 123]]);

    Http::assertSent(fn (Request $request) => $request->method() === 'POST');
});

it('throws when id is empty for pause campaign', function () {
    expect(fn () => Hunter::pauseCampaign(''))->toThrow(InvalidArgumentException::class);
});
