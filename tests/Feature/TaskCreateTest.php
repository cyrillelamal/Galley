<?php

namespace Tests\Feature;

use App\Task;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskCreateTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    const URI = '/tasks';

    /**
     * Create task in empty list of tasks.
     * @return void
     */
    public function testCreateTaskWithoutList(): void
    {
        Sanctum::actingAs(
            factory(User::class)->create(),
            ['*']
        );

        $response = $this->postJson(self::URI);

        $response->assertCreated();
    }

//    private function getData(): array
//    {
//
//    }
}
