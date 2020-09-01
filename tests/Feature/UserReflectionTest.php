<?php

namespace Tests\Feature;

use App\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserReflectionTest extends TestCase
{
    const URI = '/api/reflect_user';

    public function testNullUserReflection()
    {
        $response = $this->getJson(self::URI);

        $this->assertNull($response['user']);
    }

    public function testCurrentUserReflection()
    {
        Sanctum::actingAs(factory(User::class)->create());

        $response = $this->getJson(self::URI);

        $this->assertNotNull($response['user']);
    }
}
