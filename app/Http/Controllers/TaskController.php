<?php

namespace App\Http\Controllers;

use App\Listing;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    /**
     * Read all tasks
     *
     * @return Response
     */
    public function index()
    {
        dd('index task');
    }

    /**
     * Create a task.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'body' => ['required', 'max:511'],
            'expires_at' => ['nullable', 'date'],
//            'listing_id' => ['sometimes', 'exists:listings,id']
        ]);

        $task = new Task($data);

        /** @var Listing $listing */
        $listing = Listing::find($request->input('listing_id', null));

        DB::transaction(function () use ($task, $listing) {
            // Base case: without list.
            /** @var User $user */
            $user = Auth::user();
            $user->tasks()->save($task);

            // Extended case: with an attached listing.
            if ($listing) {
                Gate::authorize('update', $listing);

                $listing->tasks()->save($task);
            }
        });

        return \response($task, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
