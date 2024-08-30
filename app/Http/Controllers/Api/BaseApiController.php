<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class BaseApiController extends Controller
{
    protected function successResponse(array $data, int $statusCode = 200): JsonResponse
    {
        return response()->json(['data' => $data], $statusCode);
    }

    protected function errorResponse(string $message, int $statusCode = 400): JsonResponse
    {
        return response()->json(['error' => $message], $statusCode);
    }
}
