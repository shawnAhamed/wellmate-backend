<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ApiResponse
{
    public static function success(mixed $data = null, string $message = '', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public static function error(string $message, int $status = 400, array $errors = []): JsonResponse
    {
        $payload = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }

    /**
     * Wrap a paginated resource collection in the standard envelope,
     * lifting Laravel's default "data"/"meta"/"links" shape into ours.
     */
    public static function paginated(ResourceCollection $resource, string $message = ''): JsonResponse
    {
        $decoded = $resource->response()->getData(true);

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $decoded['data'],
            'meta' => $decoded['meta'] ?? [],
            'links' => $decoded['links'] ?? [],
        ]);
    }
}
