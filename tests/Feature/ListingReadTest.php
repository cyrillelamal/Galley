<?php

namespace Tests\Feature;

use App\Listing;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ListingReadTest extends TestCase
{
    use RefreshDatabase;

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
     * The user reads all his listings.
     */
    public function testReadAllListings()
    {
        Sanctum::actingAs($this->user);

        $count = $this->user->listings()->count();

        $response = $this->getJson($this->getUri());

        $response
            ->assertOk()
            ->assertJsonStructure([
                ['id', 'name'],
            ]);
        $this->assertCount($count, $response->json());
    }

    /**
     * The user reads a concrete listing.
     */
    public function testReadConcreteListing()
    {
        Sanctum::actingAs($this->user);

        /** @var Listing $listing */
        $listing = $this->user->listings()->inRandomOrder()->first();

        $response = $this->getJson($this->getUri($listing));

        $response
            ->assertOk()
            ->assertJsonStructure(['id', 'name']);
        $this->assertEquals($listing->id, $response['id']);
    }

    /**
     * The malicious user tries to read listing of another user.
     */
    public function testReadListingUnauthorizedError()
    {
        $evilUser = factory(User::class)->create();
        Sanctum::actingAs($evilUser);

        /** @var Listing $listing */
        $listing = $this->user->listings()->inRandomOrder()->first();

        $response = $this->getJson($this->getUri($listing));
        $response->assertStatus(403);
    }

    private function getUri(?Listing $listing = null): string
    {
        $uri = '/api/listings';

        if ($listing) {
            $uri .= '/' . urlencode($listing->id);
        }

        return $uri;
    }
}
