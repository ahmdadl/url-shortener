<?php

namespace App\Http\Controllers;

use App\Enums\Enums\ShortenerMethodEnum;
use App\Http\Resources\UrlResource;
use App\Services\ShortenerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class ShortenerController extends Controller
{
    public function __construct(
        private ShortenerService $shortenerService
    ) {
    }

    /**
     * create new short url
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|url|max:255',
            'method' => ['nullable', Rule::enum(ShortenerMethodEnum::class), 'max:255'],
        ]);

        try {
            $url = $this->shortenerService->createNewShortUrl($request->url);

            return response()->json([
                'url' => new UrlResource($url),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * get original url
     */
    public function show(Request $request): JsonResponse
    {
        $request->validate([
            'shortCode' => 'required|string|max:255|exists:urls,short_code',
        ]);

        try {
            $url = $this->shortenerService->getUrl($request->shortCode);

            return response()->json([
                'url' => new UrlResource($url),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * update short url
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'shortCode' => 'required|string|max:255|exists:urls,short_code',
            'url' => 'required|url|max:255',
        ]);

        try {
            $url = $this->shortenerService->updateShortUrl($request->shortCode, $request->url);

            return response()->json([
                'url' => new UrlResource($url),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * delete short url
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'shortCode' => 'required|string|max:255|exists:urls,short_code',
        ]);

        try {
            $this->shortenerService->deleteShortUrl($request->shortCode);

            return response()->json([
                'message' => 'Url deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * get clicks
     */
    public function getClicks(Request $request): JsonResponse
    {
        $request->validate([
            'shortCode' => 'required|string|max:255|exists:urls,short_code',
        ]);

        try {
            $clicks = $this->shortenerService->getClicks($request->shortCode);

            return response()->json([
                'clicks' => $clicks,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * increase clicks
     */
    public function increaseClicks(Request $request): JsonResponse
    {
        $request->validate([
            'shortCode' => 'required|string|max:255|exists:urls,short_code',
        ]);

        try {
            $this->shortenerService->increaseClicks($request->shortCode);

            return response()->json([
                'message' => 'Clicks increased successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
