<?php

namespace App\Policies;

use App\Listing;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ListingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Listing $listing
     * @return mixed
     */
    public function view(User $user, Listing $listing)
    {
        return $this->isOwner($user, $listing);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Listing $listing
     * @return mixed
     */
    public function update(User $user, Listing $listing)
    {
        return $this->isOwner($user, $listing);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Listing $listing
     * @return mixed
     */
    public function delete(User $user, Listing $listing)
    {
        return $this->isOwner($user, $listing);
    }

    private function isOwner(User $user, Listing $listing): bool
    {
        return $user->id === $listing->user->id;
    }
}
