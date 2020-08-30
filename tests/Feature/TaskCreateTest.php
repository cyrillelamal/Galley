<?php

namespace Tests\Feature;

use App\Listing;
use App\Task;
use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskCreateTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    const URI = '/api/tasks';

    /** @var User */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::all()->random();
    }

    /**
     * Unauthenticated users cannot create any tasks.
     */
    public function testUnauthorizedUserError()
    {
        $response = $this->postJson(self::URI, $this->getRequestData(), $this->getRequestHeaders());

        $response->assertStatus(401);
    }

    /**
     * Create a task that doesn't belong to any listing.
     */
    public function testCreateTaskWithoutList(): void
    {
        Sanctum::actingAs($this->user, ['*']);

        $count = $this->user->tasks()->count();

        $response = $this->postJson(self::URI, $this->getRequestData(), $this->getRequestHeaders());

        $this->assertEquals($count + 1, $this->user->tasks()->count());
        $response
            ->assertCreated()
            ->assertJsonStructure(['id']);

        /** @var Task $task */
        $task = Task::find($response['id']);

        $this->assertNotNull($task);
        $this->assertNull($task->listing, "Task is attached to a list.");
    }

    /**
     * Create task that belongs to a listing.
     */
    public function testCreateTaskWithList(): void
    {
        Sanctum::actingAs($this->user, ['*']);

        /** @var Listing $listing */
        $listing = \factory(Listing::class)->make();
        $this->user->listings()->save($listing);

        $data = $this->getRequestData($listing);

        $totalUserTasksCount = $this->user->tasks()->count();
        $listingTasksCount = $listing->tasks()->count();

        $response = $this->postJson(self::URI, $data, $this->getRequestHeaders());

        $this->assertEquals($totalUserTasksCount + 1, $this->user->tasks()->count());
        $this->assertEquals($listing->id, $response['listing_id']);
        $this->assertEquals($listingTasksCount + 1, $listing->tasks()->count());
        $response
            ->assertCreated()
            ->assertJsonStructure(['id']);

        /** @var Task $task */
        $task = Task::find($response['id']);

        $listing = $task->listing;
        $this->assertNotNull($listing);
        $this->assertEquals($data['listing_id'], $listing->id);
    }

    /**
     * Create task without expiration date.
     */
    public function testCreateWithoutExpirationDate()
    {
        Sanctum::actingAs($this->user, ['*']);

        $data = $this->getRequestData();
        unset($data['expires_at']);

        $count = $this->user->tasks()->count();

        $response = $this->postJson(self::URI, $data, $this->getRequestHeaders());

        self::assertEquals($count + 1, $this->user->tasks()->count());
        $response
            ->assertCreated()
            ->assertJsonStructure(['id']);

        // The newly created task.
        /** @var Task $task */
        $task = Task::find($response['id']);
        $this->assertNull($task->expires_at);
    }

    /**
     * Fail to create a new task because tasks cannot exist without body.
     */
    public function testCreateEmptyTaskError()
    {
        Sanctum::actingAs($this->user, ['*']);

        $data = $this->getRequestData();
        unset($data['body']);

        $count = $this->user->tasks()->count();

        $response = $this->postJson(self::URI, $data, $this->getRequestHeaders());

        self::assertEquals($count, $this->user->tasks()->count(), "A new task has been created without body.");
        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['body']
            ]);
    }

    private function getRequestData(?Listing $listing = null): array
    {
        $faker = Factory::create();

        $data = [
            'body' => $faker->text,
            'expires_at' => $faker->dateTime->format(DATE_ATOM),
        ];

        if ($listing) {
            $data['listing_id'] = $listing->id;
        }

        return $data;
    }

    private function getRequestHeaders(): array
    {
        return [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ];
    }
}
