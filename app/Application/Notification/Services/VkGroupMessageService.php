<?php

namespace App\Application\Notification\Services;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use RuntimeException;

class VkGroupMessageService
{
    private const VK_MESSAGES_SEND_URL = 'https://api.vk.com/method/messages.send';

    private readonly string $groupAccessToken;

    private readonly string $apiVersion;

    public function __construct()
    {
        $this->groupAccessToken = (string) config('services.vk.group_access_token', '');
        $this->apiVersion = (string) config('services.vk.api_version', '5.199');

        if ($this->groupAccessToken === '') {
            throw new InvalidArgumentException('VK group access token is not configured.');
        }
    }

    public function sendToUser(int $userId, string $message): int
    {
        if ($userId <= 0) {
            throw new InvalidArgumentException('User id must be a positive integer.');
        }

        $normalizedMessage = trim($message);
        if ($normalizedMessage === '') {
            throw new InvalidArgumentException('Message must not be empty.');
        }

        $response = Http::asForm()->post(self::VK_MESSAGES_SEND_URL, [
            'access_token' => $this->groupAccessToken,
            'v' => $this->apiVersion,
            'user_id' => $userId,
            'message' => $normalizedMessage,
            'random_id' => random_int(1, PHP_INT_MAX),
        ]);

        if ($response->failed()) {
            throw new RuntimeException('VK API request failed with status '.$response->status().'.');
        }

        $payload = $response->json();

        if (isset($payload['error'])) {
            $errorCode = $payload['error']['error_code'] ?? 'unknown';
            $errorMessage = $payload['error']['error_msg'] ?? 'Unknown VK API error.';
            throw new RuntimeException('VK API error '.$errorCode.': '.$errorMessage);
        }

        if (! isset($payload['response'])) {
            throw new RuntimeException('VK API returned an unexpected response payload.');
        }

        return (int) $payload['response'];
    }
}
