<?php

namespace Tests\Feature;

use App\Task;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskDeleteTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var User
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::all()->random();
    }

    /**
     * The user correctly deletes his task.
     */
    public function testDeleteTaskCorrectly()
    {
        Sanctum::actingAs($this->user);

        /** @var Task $task */
        $task = $this->user->tasks()->inRandomOrder()->first();
        $id = $task->id;

        $response = $this->deleteJson($this->getUri($task));

        $response->assertStatus(200);
        $this->assertNull(Task::find($id));
    }

    /**
     * The user tries to delete a task of another user.
     */
    public function testDeleteTaskUnauthorizedUserError()
    {
        Sanctum::actingAs(factory(User::class)->create());

        /** @var Task $task */
        $task = $this->user->tasks()->inRandomOrder()->first();
        $id = $task->id;

        $response = $this->deleteJson($this->getUri($task));

        $response->assertStatus(403);
        $this->assertNotNull(Task::find($id));
    }

    private function getUri(Task $task): string
    {
        return '/api/tasks/' . urlencode($task->id);
    }
}
