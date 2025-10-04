<?php

namespace App\Services;

use App\interfaces\ShortenerMethod;
use App\Models\Url;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\DB;

final class ShortenerService
{
    public function __construct(
        #[CurrentUser]
        private User $user,
        private ShortenerMethod $shortenerMethod
    ) {
    }

    /**
     * create new short url
     */
    public function createNewShortUrl(string $url): Url
    {
        return DB::transaction(function () use ($url) {
            $shortCode = $this->shortenerMethod->shorten($url);

            return Url::create([
                'url' => $url,
                'short_code' => $shortCode,
                'user_id' => $this->user?->id,
                'shorten_method' => $this->shortenerMethod::class,
            ]);
        });
    }

    /**
     * get original url
     */
    public function getUrl(string $shortCode): Url
    {
        return Url::where('short_code', $shortCode)->first();
    }

    /**
     * update short url
     */
    public function updateShortUrl(string $shortCode, string $originalUrl): Url
    {
        $url = $this->getUrl($shortCode);

        if (!$url) {
            throw new \Exception('Url not found');
        }

        $url->update([
            'url' => $originalUrl,
        ]);

        return $url;
    }

    /**
     * delete short url
     */
    public function deleteShortUrl(string $shortCode): void
    {
        $url = $this->getUrl($shortCode);

        if (!$url) {
            throw new \Exception('Url not found');
        }

        $url->delete();
    }

    /**
     * increase clicks
     */
    public function increaseClicks(string $shortCode): void
    {
        $url = $this->getUrl($shortCode);

        if (!$url) {
            throw new \Exception('Url not found');
        }

        $url->increment('clicks');
    }

    /**
     * get clicks
     */
    public function getClicks(string $shortCode): int
    {
        $url = $this->getUrl($shortCode);

        if (!$url) {
            throw new \Exception('Url not found');
        }

        return $url->clicks;
    }
}
