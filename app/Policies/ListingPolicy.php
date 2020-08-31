<?php

namespace App\Policies;

use App\Listing;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ListingPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return (bool)$user;
    }

    /**
     * Determine whether the user can view the listing.
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
     * Determine whether the user can create listings.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return (bool)$user;
    }

    /**
     * Determine whether the user can update the listing.
     *
     * @param User $user
     * @param Listing|null $listing
     * @return mixed
     */
    public function update(User $user, ?Listing $listing)
    {
        if (null === $listing) {
            return true;
        }

        return $this->isOwner($user, $listing);
    }

    /**
     * Determine whether the user can delete the listing.
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
