<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\AdvertisementTaskProducerInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class AdvertisementTaskController extends Controller
{
    public function __construct(
        private readonly AdvertisementTaskProducerInterface $taskProducer,
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'catalog_id' => ['required', 'integer', 'exists:cats,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'ad_type' => ['required', 'in:sell,buy'],
            'photo_paths' => ['required', 'array', 'min:1'],
            'photo_paths.*' => ['required', 'string', 'max:2048'],
        ]);

        $payload = [
            'catalog_id' => (int) $validated['catalog_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'ad_type' => $validated['ad_type'],
            'photo_paths' => array_values($validated['photo_paths']),
        ];

        try {
            $taskId = $this->taskProducer->dispatchCreateTask((int) $validated['user_id'], $payload);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Failed to enqueue advertisement creation task.',
                'error' => $e->getMessage(),
            ], 503);
        }

        return response()->json([
            'message' => 'Task accepted.',
            'data' => [
                'task_id' => $taskId,
                'status' => 'queued',
            ],
        ], 202);
    }
}
