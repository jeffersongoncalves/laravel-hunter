<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Hunter\Facades\Hunter;

it('finds an email', function () {
    Http::fake([
        'api.hunter.io/v2/email-finder*' => Http::response(['data' => ['email' => 'jane@example.com']], 200),
    ]);

    expect(Hunter::findEmail('example.com', 'Jane', 'Doe'))->toBe(['data' => ['email' => 'jane@example.com']]);

    Http::assertSent(fn (Request $request) => $request['domain'] === 'example.com'
        && $request['first_name'] === 'Jane'
        && $request['last_name'] === 'Doe');
});

it('throws when first name is empty for email finder', function () {
    expect(fn () => Hunter::findEmail('example.com', '', 'Doe'))->toThrow(InvalidArgumentException::class);
});

it('verifies an email', function () {
    Http::fake([
        'api.hunter.io/v2/email-verifier*' => Http::response(['data' => ['status' => 'valid']], 200),
    ]);

    expect(Hunter::verifyEmail('jane@example.com'))->toBe(['data' => ['status' => 'valid']]);

    Http::assertSent(fn (Request $request) => $request['email'] === 'jane@example.com');
});

it('throws when email is empty for email verifier', function () {
    expect(fn () => Hunter::verifyEmail(''))->toThrow(InvalidArgumentException::class);
});
