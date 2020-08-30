<?php

namespace App\Policies;

use App\Task;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return (bool)$user;
    }

    /**
     * Determine whether the user can view the task.
     *
     * @param User $user
     * @param Task $task
     * @return mixed
     */
    public function view(User $user, Task $task)
    {
        return $this->isOwner($user, $task);
    }

    /**
     * Determine whether the user can create tasks.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return (bool)$user;
    }

    /**
     * Determine whether the user can update the task.
     *
     * @param User $user
     * @param Task|null $task
     * @return mixed
     */
    public function update(User $user, ?Task $task)
    {
        if (null === $task) {
            return true;
        }

        return $this->isOwner($user, $task);
    }

    /**
     * Determine whether the user can delete the task.
     *
     * @param User $user
     * @param Task $task
     * @return mixed
     */
    public function delete(User $user, Task $task)
    {
        return $this->isOwner($user, $task);
    }

    private function isOwner(User $user, Task $task): bool
    {
        return $user->id === $task->user->id;
    }
}
