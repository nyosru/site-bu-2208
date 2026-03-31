<?php

namespace Tests\Feature\Api;

use App\Models\Cat;
use App\Models\User;
use App\Support\AdvertisementTaskProducerInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class AdvertisementTaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_enqueues_advertisement_task(): void
    {
        $user = User::query()->create([
            'name' => 'API User',
            'email' => 'api-user@example.com',
            'password' => Hash::make('password'),
        ]);
        $catalog = Cat::query()->create([
            'name' => 'Телефоны',
            'cat_up_id' => null,
        ]);

        $producer = Mockery::mock(AdvertisementTaskProducerInterface::class);
        $producer->shouldReceive('dispatchCreateTask')
            ->once()
            ->with(
                $user->id,
                Mockery::on(fn (array $payload): bool => $payload['title'] === 'iPhone 14')
            )
            ->andReturn('task-123');

        $this->app->instance(AdvertisementTaskProducerInterface::class, $producer);

        $response = $this->postJson('/api/advertisement-tasks', [
            'user_id' => $user->id,
            'catalog_id' => $catalog->id,
            'title' => 'iPhone 14',
            'description' => 'Состояние отличное',
            'price' => 100000,
            'ad_type' => 'sell',
            'photo_paths' => ['/storage/a.jpg', '/storage/b.jpg'],
        ]);

        $response
            ->assertStatus(202)
            ->assertJsonPath('data.task_id', 'task-123')
            ->assertJsonPath('data.status', 'queued');

        $this->assertDatabaseCount('advertisements', 0);
        $this->assertDatabaseCount('advertisement_photos', 0);
    }
}
