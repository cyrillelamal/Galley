<?php

use App\Listing;
use App\Task;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListingSeeder extends Seeder
{
    /**
     * Number if user's lists + no lists.
     */
    const LISTINGS_RANGE = [1, 3];

    /**
     * For each user create a random number of listings.
     * For each listing attach zero or more tasks.
     *
     * @return void
     */
    public function run()
    {
        list($min, $max) = self::LISTINGS_RANGE;

        User::all()->each(function (User $user) use ($min, $max) {
            // Users can have no lists.
            if (mt_rand(0, 1)) {
                return;
            }

            $user->listings()->createMany(
                factory(Listing::class, rand($min, $max))->make()->toArray()
            );
        });

        Listing::all()->each(function (Listing $listing) {
            // Lists can be empty.
            /** @var User $user */
            $user = $listing->user;
            /** @var Collection $tasks */
            $tasks = $user->tasks;

            $limit = rand(0, $tasks->count());

            $tasks->each(function (Task $task) use ($listing, $limit) {
                // The task belongs to user and the list also belongs to the user.
                // Like user moves the task in another list.
                if ($listing->user->id === $task->user->id && $listing->tasks()->count() < $limit) {
                    $listing->tasks()->save($task);
                }
            });
        });
    }
}
