<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Services\ShortenerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShowShortenUrlController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Url $url, ShortenerService $shortenerService): RedirectResponse
    {
        dispatch(function () use ($url, $shortenerService) {
            $shortenerService->increaseClicks($url->short_code);
        })->afterResponse();

        return redirect()->away($url->url);
    }
}
