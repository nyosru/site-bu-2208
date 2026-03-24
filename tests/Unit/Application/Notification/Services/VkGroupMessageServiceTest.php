<?php

namespace Tests\Unit\Application\Notification\Services;

use App\Application\Notification\Services\VkGroupMessageService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

class VkGroupMessageServiceTest extends TestCase
{
    public function test_send_to_user_returns_message_id(): void
    {
        Config::set('services.vk.group_access_token', 'test-token');
        Config::set('services.vk.api_version', '5.199');

        Http::fake([
            'https://api.vk.com/method/messages.send' => Http::response([
                'response' => 987654,
            ]),
        ]);

        $service = new VkGroupMessageService;

        $result = $service->sendToUser(100500, ' Тестовое сообщение ');

        $this->assertSame(987654, $result);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.vk.com/method/messages.send'
                && $request['access_token'] === 'test-token'
                && $request['v'] === '5.199'
                && $request['user_id'] === 100500
                && $request['message'] === 'Тестовое сообщение'
                && is_int($request['random_id'])
                && $request['random_id'] > 0;
        });
    }

    public function test_send_to_user_throws_runtime_exception_when_vk_returns_error(): void
    {
        Config::set('services.vk.group_access_token', 'test-token');
        Config::set('services.vk.api_version', '5.199');

        Http::fake([
            'https://api.vk.com/method/messages.send' => Http::response([
                'error' => [
                    'error_code' => 901,
                    'error_msg' => 'Can not send messages for users from blacklist',
                ],
            ]),
        ]);

        $service = new VkGroupMessageService;

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('VK API error 901: Can not send messages for users from blacklist');

        $service->sendToUser(100500, 'Тест');
    }
}
