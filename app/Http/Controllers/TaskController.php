<?php

namespace App\Http\Controllers;

use App\Listing;
use App\Task;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

/**
 * The controller uses "Resource Controller Policy".
 * Watch out the constructor.
 */
class TaskController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Task::class, 'task');
    }

    /**
     * Read all tasks
     *
     * @return Collection
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->tasks;
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
     * Display the specific task.
     *
     * @param Task $task
     * @return Task
     */
    public function show(Task $task)
    {
        return $task;
    }

    /**
     * Update the specified task in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, Task $task)
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
