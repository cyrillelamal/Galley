<?php

use App\Task;
use App\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    const TASKS_RANGE = [10, 22];

    /**
     * For each user create some tasks.
     *
     * @return void
     */
    public function run()
    {
        list($min, $max) = self::TASKS_RANGE;

        User::all()->each(function (User $user) use ($min, $max) {
            $user->tasks()->createMany(
                factory(Task::class, rand($min, $max))->make()->toArray()
            );
        });
    }
}
