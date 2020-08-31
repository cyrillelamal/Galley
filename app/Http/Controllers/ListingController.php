<?php

namespace App\Http\Controllers;

use App\Listing;
use App\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ListingController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Listing::class, 'listing');
    }

    /**
     * Display all user's listings.
     *
     * @return Collection
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->listings;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $data = $request->validate(Listing::getValidationRules());

        $listing = new Listing($data);

        $user->listings()->save($listing);

        return response($listing, 201);
    }

    /**
     * Display the specified listing.
     *
     * @param Listing $listing
     * @return Listing|Builder|Model|object
     */
    public function show(Listing $listing)
    {
        return Listing::with('tasks')
            ->where('listings.id', $listing->id)
            ->first();
    }

    /**
     * Update the specified listing in storage.
     *
     * @param Request $request
     * @param Listing $listing
     * @return Listing
     */
    public function update(Request $request, Listing $listing)
    {
        $data = $request->validate(Listing::getValidationRules(true));

        $listing->update($data);

        return $listing;
    }

    /**
     * Remove the specified listing from storage.
     *
     * @param Listing $listing
     * @return Response
     * @throws Exception
     */
    public function destroy(Listing $listing)
    {
        $listing->delete();

        return \response(null, 200);
    }
}
