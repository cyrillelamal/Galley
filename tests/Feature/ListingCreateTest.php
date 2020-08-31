<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Iterator;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ListingCreateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const URI = '/api/listings';

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
     * The user creates listing correctly.
     */
    public function testCreateListingCorrectly()
    {
        Sanctum::actingAs($this->user);

        $count = $this->user->listings()->count();

        $data = $this->getRequestData();

        $response = $this->postJson(self::URI, $data);

        $response
            ->assertCreated()
            ->assertJsonStructure(['id']);
        $this->assertEquals($count + 1, $this->user->listings()->count());
    }

    /**
     * The user provides incorrect data.
     */
    public function testCreateListingIncorrectDataError()
    {
        Sanctum::actingAs($this->user);

        $count = $this->user->listings()->count();

        foreach ($this->incorrectRequestDataGenerator() as $data) {
            $response = $this->postJson(self::URI, $data);

            $response->assertStatus(422);

            $this->assertEquals($count, $this->user->listings()->count());
        }
    }

    private function incorrectRequestDataGenerator(): Iterator
    {
        $data = $this->getRequestData();
        $data['name'] = null;
        yield $data;

        $data['name'] = '';
        yield $data;

        unset($data['name']);
        yield $data;
    }

    private function getRequestData(): array
    {
        return [
            'name' => $this->faker->words(rand(3, 5), true),
        ];
    }
}
