<?php

use App\Models\Url;

use function Pest\Laravel\get;

it('redirects to original url', function () {
   $url = Url::factory()->createOne([
    'url' => $originalUrl = fake()->url(),
   ]);

   expect($url->clicks)->toBe(0);

   get(route('urls.show', $url->short_code))->assertRedirect($originalUrl);

   expect($url->fresh()->clicks)->toBe(1);
});
