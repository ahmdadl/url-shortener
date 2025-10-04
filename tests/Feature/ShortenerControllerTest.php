<?php

use App\Enums\Enums\ShortenerMethodEnum;
use App\Models\Url;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

it('can shorten url to string only', function () {
    postJson(route('shorten.store'), [
        'url' => $originalUrl = fake()->url(),
        'method' => ShortenerMethodEnum::STRING,
    ])->assertCreated();

    assertDatabaseHas('urls', [
        'url' => $originalUrl,
    ]);

    $url = Url::query()->first();

    expect($url->short_code)->toBeAlpha();
});

it('can shorten url to number only', function () {
    postJson(route('shorten.store'), [
        'url' => $originalUrl = fake()->url(),
        'method' => ShortenerMethodEnum::NUMBER,
    ])->assertCreated();

    assertDatabaseHas('urls', [
        'url' => $originalUrl,
    ]);

    $url = Url::query()->first();

    expect($url->short_code)->toBeNumeric();
});
    
it('can shorten url to mixed only', function () {
    postJson(route('shorten.store'), [
        'url' => $originalUrl = fake()->url(),
        'method' => ShortenerMethodEnum::MIXED,
    ])->assertCreated();

    assertDatabaseHas('urls', [
        'url' => $originalUrl,
    ]);

    $url = Url::query()->first();

    expect($url->short_code)->toBeAlphaNumeric();
});

it('can shorten url to string by default', closure: function () {
    postJson(route('shorten.store'), [
        'url' => $originalUrl = fake()->url(),
    ])->assertCreated();

    assertDatabaseHas('urls', [
        'url' => $originalUrl,
    ]);

    $url = Url::query()->first();

    expect($url->short_code)->toBeAlpha();
});