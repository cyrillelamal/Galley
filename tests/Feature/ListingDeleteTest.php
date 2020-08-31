<?php

namespace Tests\Feature;

use App\Listing;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ListingDeleteTest extends TestCase
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

        $listing = factory(Listing::class)->make();

        $this->user->listings()->save($listing);
    }

    /**
     * The user tries to remove his listing.
     */
    public function testDeleteListingCorrectly()
    {
        Sanctum::actingAs($this->user);

        /** @var Listing $listing */
        $listing = $this->user->listings()->inRandomOrder()->first();
        $id = $listing->id;

        $response = $this->deleteJson($this->getUri($listing));

        $response->assertOk();
        $this->assertNull(Listing::find($id));
    }

    /**
     * The listing doesn't exist.
     */
    public function testDeleteUndefinedListingError()
    {
        Sanctum::actingAs($this->user);

        /** @var Listing $listing */
        $listing = $this->user->listings()->inRandomOrder()->first();
        $id = $listing->id;

        $uri = $this->getUri($listing) . urlencode(uniqid());

        $response = $this->deleteJson($uri);

        $response->assertNotFound();
        $this->assertNotNull(Listing::find($id));
    }

    /**
     * The malicious user tries to remove a listing of another user.
     */
    public function testDeleteListingUnauthorizedUserError()
    {
        /** @var User $evilUser */
        $evilUser = factory(User::class)->create();
        Sanctum::actingAs($evilUser);

        /** @var Listing $listing */
        $listing = $this->user->listings()->inRandomOrder()->first();

        $response = $this->deleteJson($this->getUri($listing));

        $response->assertStatus(403);
        $this->assertNotNull(Listing::find($listing->id));
    }

    private function getUri(Listing $listing): string
    {
        return '/api/listings/' . urlencode($listing->id);
    }


}
