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

/**
 * The controller uses "Resource Controller Policy".
 * Watch out the constructor.
 */
class ListingController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Listing::class, 'listing');
    }

    /**
     * @OA\Get(
     *     path="/api/listings",
     *     summary="Get all user's listings.",
     *     tags={"listing", "read"},
     *     @OA\Response(
     *     response=200,
     *     description="List of user's listings.",
     *     @OA\JsonContent(
     *          @OA\Property(
     *              property="listings",
     *              type="array",
     *              @OA\Items(type="object", ref="#/components/schemas/Listing")
     *          )
     *       )
     *      ),
     *     @OA\Response(
     *     response=401,
     *     description="The user is unauthorized."
     * )
     * )
     * @return Collection
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->listings;
    }

    /**
     * @OA\Post(
     *     path="/api/listings",
     *     summary="Create a listing.",
     *     description="Create a new listing.",
     *     tags={"listing", "create"},
     *     @OA\Response(
     *      response=201,
     *      description="Created successfuly.",
     *      @OA\JsonContent(
     *          @OA\Property(
     *          property="listing",
     *          type="object",
     *          ref="#/components/schemas/Listing"
     * )
     * )
     * ),
     *     @OA\Response(
     *     response=422,
     *     description="Invalid data. Unable to create a new listing using the provided data."
     * ),
     *     @OA\Response(
     *     response=401,
     *     description="The user is unauthorized."
     * )
     * )
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
     * @OA\Get(
     *     path="/api/listings/{id}",
     *     description="Get a listing by its id.",
     *     @OA\Parameter(
     *     in="path",
     *     name="id",
     *     required=true,
     *     example=123,
     *     description="ID of the listing",
     *     @OA\Schema(type="integer")
     * ),
     *     @OA\Response(
     *      response=200,
     *      description="The listing data.",
     *     @OA\JsonContent(
     *     @OA\Property(property="listing", type="object", ref="#/components/schemas/Listing")
     *      )
     * ),
     *     @OA\Response(
     *     response=401,
     *     description="The user is unauthorized."
     * )
     * )
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
     * @OA\Patch(
     *     path="/api/listings/{id}",
     *     summary="Update the listing.",
     *     description="Update the listing.",
     *     tags={"listing", "update"},
     *     @OA\Parameter(
     *      in="path",
     *      name="id",
     *      required=true,
     *      example=123,
     *      description="ID of the listing",
     *      @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *      response=200,
     *      description="Updated successfuly.",
     *      @OA\JsonContent(
     *          @OA\Property(
     *              property="listing",
     *              type="object",
     *              ref="#/components/schemas/Listing"
     *          )
     *      )
     *     ),
     *     @OA\Response(
     *      response=422,
     *      description="Invalid data. Unable to update the listing using the provided data."
     *     ),
     *     @OA\Response(
     *      response=401,
     *      description="The user is unauthorized."
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/listings/{id}",
     *     summary="Delete the listing and all related tasks.",
     *     description="Delete the listing and also all its related tasks.",
     *     tags={"listing", "delete"},
     *     @OA\Parameter(
     *      in="path",
     *      name="id",
     *      required=true,
     *      example=123,
     *      description="ID of the listing",
     *      @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *      response=200,
     *      description="Removed successfuly."
     *     ),
     *     @OA\Response(
     *      response=401,
     *      description="The user is unauthorized."
     *     )
     * )
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
