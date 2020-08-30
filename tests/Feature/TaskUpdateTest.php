<?php

namespace Tests\Feature;

use App\Listing;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskUpdateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @var User */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::all()->random();
    }

    /**
     * The user updates his task without moving it to another listing.
     */
    public function testUpdateTaskParameters()
    {
        Sanctum::actingAs($this->user, ['*']);

        /** @var Task $task */
        $task = $this->user->tasks()->inRandomOrder()->first();
        $id = $task->id;

        // Another data.
        $data = $this->getRequestData($task);

        $response = $this->putJson($this->getUri($task), $data);

        $response->assertOk();

        $updatedTask = Task::find($id);

        $this->assertEquals($data['body'], $updatedTask->body);
        $this->assertEquals($data['expires_at'], $updatedTask->expires_at);

        // Partial data.
        $partialData = $this->getRequestData($updatedTask);
        unset($partialData['body']); // Do not update body.
        $body = $updatedTask->body;

        $response = $this->putJson($this->getUri($updatedTask), $partialData);

        $response->assertOk();

        $updatedTask = Task::find($updatedTask->id);
        $this->assertEquals($body, $updatedTask->body, "The body has been updated.");
    }

    /**
     * E.g. the user tries to set an empty body.
     */
    public function testUpdateTaskIncorrectParameters()
    {
        Sanctum::actingAs($this->user, ['*']);

        /** @var Task $task */
        $task = $this->user->tasks()->inRandomOrder()->first();
        $id = $task->id;
        $body = $task->body;

        $data = $this->getRequestData($task);
        $data['body'] = '';

        $response = $this->putJson($this->getUri($task), $data);

        $response->assertStatus(422);

        // Not updated task.
        $updatedTask = Task::find($id);
        $this->assertEquals($body, $updatedTask->body);
    }

    /**
     * The user associates the task with another listing.
     */
    public function testUpdateTaskListing()
    {
        Sanctum::actingAs($this->user, ['*']);

        /** @var Listing $listing */
        $listing = factory(Listing::class)->make();
        $this->user->listings()->save($listing);

        /** @var Task $task */
        $task = $this->user->tasks()->inRandomOrder()->first();
        $oldListing = $task->listing;

        $data = $this->getRequestData($task, $listing);

        $response = $this->putJson($this->getUri($task), $data);

        $response->assertOk();

        $task->refresh();

        $this->assertNotEquals($this->getListingId($oldListing), $task->listing->id);
    }

    /**
     * This is normally a 500 error, or a vary advanced hack from user.
     * The user tries to violate task -> listing association by changing id.
     */
    public function testUpdateIncorrectTaskListing()
    {
        Sanctum::actingAs($this->user, ['*']);

        /** @var Listing $listing */
        $listing = factory(Listing::class)->make();
        $this->user->listings()->save($listing);

        /** @var Task $task */
        $task = $this->user->tasks()->inRandomOrder()->first();
        $oldListing = $task->listing;

        $data = $this->getRequestData($task, $listing);
        // Incorrect or undefined listing.
        $data['listing_id'] .= '9000';

        $response = $this->putJson($this->getUri($task), $data);

        $response->assertStatus(404);

        $task->refresh();
        $listing->refresh();

        $this->assertEquals($this->getListingId($oldListing), $this->getListingId($task->listing));
    }

    private function getRequestData(Task $task, ?Listing $listing = null): array
    {
        $expiresAt = $task->expires_at;

        if (null === $expiresAt) {
            $expiresAt = Carbon::createFromTimestamp(time());
        } else {
            $expiresAt = $task->expires_at->addDays(2);
        }

        $data = [
            'body' => 'New task body!',
            'expires_at' => (string)$expiresAt,
        ];

        if ($listing) {
            $data['listing_id'] = urlencode($listing->id);
        }

        return $data;
    }

    private function getUri(Task $task): string
    {
        return '/api/tasks/' . urlencode($task->id);
    }

    private function getListingId(?Listing $listing): ?int
    {
        if ($listing) {
            return $listing->id;
        }

        return null;
    }
}
