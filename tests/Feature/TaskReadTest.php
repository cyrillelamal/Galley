<?php

namespace Tests\Feature;

use App\Task;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskReadTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @var User */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::all()->random();
    }

    /**
     * Unauthenticated users cannot access any tasks.
     */
    public function testUnauthorizedUserError()
    {
        $response = $this->getJson($this->getUri());

        $response->assertStatus(401);

        $attackedTask = $this->getRandomTask();

        $response = $this->getJson($this->getUri($attackedTask));

        $response->assertStatus(401);
    }

    /**
     * Correctly read all user's tasks.
     */
    public function testReadAllUsersTasks()
    {
        Sanctum::actingAs($this->user, ['*']);

        $response = $this->getJson($this->getUri());

        $response->assertOk();
        foreach ($response->json() as $task) {
            self::assertEquals($this->user->id, $task['user_id']);
        }
    }

    /**
     * Users can only look up their own tasks, not the tasks of other users.
     */
    public function testReadOneTask()
    {
        Sanctum::actingAs($this->user, ['*']);

        $task = $this->getRandomTask($this->user);

        $response = $this->getJson($this->getUri($task));

        $response
            ->assertOk()
            ->assertJsonStructure(['id']);
        $this->assertEquals($this->user->id, $response['user_id']);
    }

    /**
     * A user tries to read a task of another user.
     */
    public function testReadOneUnauthorizedTask()
    {
        // He even has no tasks.
        /** @var User $evilUser */
        $evilUser = factory(User::class)->create();

        Sanctum::actingAs($evilUser, ['*']);

        $attackedTask = $this->getRandomTask($this->user);

        $response = $this->getJson($this->getUri($attackedTask));

        $response->assertStatus(403);
    }

    private function getUri(?Task $task = null): string
    {
        $uri = '/api/tasks';

        if ($task) {
            $uri .= '/' . urlencode($task->id);
        }

        return $uri;
    }

    /** @noinspection PhpIncompatibleReturnTypeInspection */
    private function getRandomTask(?User $user = null): Task
    {
        if ($user) {
            return $user->tasks()->inRandomOrder()->first();
        }

        return Task::all()->random();
    }
}
