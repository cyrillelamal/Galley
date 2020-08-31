<?php

namespace Tests\Feature;

use App\Listing;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ListingUpdateTest extends TestCase
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
        $this->user->listings()->save(factory(Listing::class)->make());
    }

    /**
     * The user updates his listing with correct data.
     */
    public function testUpdateListingCorrectly()
    {
        Sanctum::actingAs($this->user);

        /** @var Listing $listing */
        $listing = $this->user->listings()->inRandomOrder()->first();

        $oldName = $listing->name;

        $data = $this->getRequestData();

        $response = $this->putJson($this->getUri($listing), $data);

        $response->assertOk();

        $listing->refresh();

        $this->assertNotEquals($oldName, $listing->name);
    }

    /**
     * The user sends incorrect data.
     */
    public function testUpdateListingIncorrectData()
    {
        Sanctum::actingAs($this->user);

        /** @var Listing $listing */
        $listing = $this->user->listings()->inRandomOrder()->first();
        $oldName = $listing->name;

        $data = $this->getRequestData();
        $data['name'] = null;

        $response = $this->putJson($this->getUri($listing), $data);

        $response->assertStatus(422);

        $listing->refresh();

        $this->assertEquals($oldName, $listing->name);
    }

    /**
     * The user tries to update listings of another user.
     */
    public function testUpdateListingUnauthorizedUserError()
    {
        $evilUser = factory(User::class)->create();

        Sanctum::actingAs($evilUser);

        /** @var Listing $listing */
        $listing = $this->user->listings()->inRandomOrder()->first();
        $oldName = $listing->name;

        $data = $this->getRequestData();

        $response = $this->putJson($this->getUri($listing), $data);

        $response->assertStatus(403);

        $listing->refresh();

        $this->assertEquals($oldName, $listing->name);
    }

    private function getRequestData(): array
    {
        return [
            'name' => 'The new listing name'
        ];
    }

    private function getUri(Listing $listing): string
    {
        return '/api/listings/' . urlencode($listing->id);
    }
}
