<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    const NUMBER_OF_USERS = 14;

    /**
     * Create users.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, self::NUMBER_OF_USERS)->create();
    }
}
