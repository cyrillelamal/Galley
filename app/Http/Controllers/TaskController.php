<?php

namespace App\Http\Controllers;

use App\Listing;
use App\Task;
use App\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

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
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="Get all user's tasks.",
     *     tags={"task", "read"},
     *     @OA\Response(
     *     response=200,
     *     description="List of user's tasks.",
     *     @OA\JsonContent(
     *          @OA\Property(
     *              property="tasks",
     *              type="array",
     *              @OA\Items(type="object", ref="#/components/schemas/Task")
     *          )
     *       )
     *      ),
     *     @OA\Response(
     *     response=401,
     *     description="The user is not authorized."
     * )
     * )
     * @return Collection
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->tasks;
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     summary="Create a task.",
     *     description="Create a new task.",
     *     tags={"task", "create"},
     *     @OA\Response(
     *      response=201,
     *      description="Created successfuly.",
     *      @OA\JsonContent(
     *          @OA\Property(
     *          property="task",
     *          type="object",
     *          ref="#/components/schemas/Task"
     * )
     * )
     * ),
     *     @OA\Response(
     *     response=422,
     *     description="Invalid data. Unable to create a task using the provided data."
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
        $data = $request->validate(Task::getValidationRules());

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
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     description="Get a task by id",
     *     @OA\Parameter(
     *     in="path",
     *     name="id",
     *     required=true,
     *     example=123,
     *     description="ID of the task",
     *     @OA\Schema(
     *     type="integer"
     * )
     * ),
     *     @OA\Response(
     *      response=200,
     *      description="The task data.",
     *     @OA\JsonContent(
     *     @OA\Property(property="task", type="object", ref="#/components/schemas/Task")
     *      )
     * ),
     *     @OA\Response(
     *     response=401,
     *     description="The user is unauthorized."
     * )
     * )
     * @param Task $task
     * @return Task
     */
    public function show(Task $task)
    {
        return $task;
    }

    /**
     * @OA\Patch(
     *     path="/api/tasks/{id}",
     *     summary="Update the task.",
     *     description="Update the task.",
     *     tags={"task", "update"},
     *     @OA\Parameter(
     *      in="path",
     *      name="id",
     *      required=true,
     *      example=123,
     *      description="ID of the task",
     *      @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *      response=200,
     *      description="Updated successfuly.",
     *      @OA\JsonContent(
     *          @OA\Property(
     *              property="task",
     *              type="object",
     *              ref="#/components/schemas/Task"
     *          )
     *      )
     *     ),
     *     @OA\Response(
     *      response=422,
     *      description="Invalid data. Unable to update the task using the provided data."
     *     ),
     *     @OA\Response(
     *      response=401,
     *      description="The user is unauthorized."
     *     )
     * )
     * @param Request $request
     * @param Task $task
     * @return Task
     */
    public function update(Request $request, Task $task)
    {
        $data = $request->validate(Task::getValidationRules(true));

        if ($request->has('listing_id')) {
            $listing = Listing::findOrFail($request->input('listing_id'));

            // The task owner is checked already.
            // The listing owner is checked here.
            Gate::authorize('update', $listing);

            if (null === $listing) {
                $task->listing()->dissociate();
            } else {
                $task->listing()->associate($listing);
            }
        }

        $task->update($data);

        return $task; // 200
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     summary="Delete the task.",
     *     description="Delete the task.",
     *     tags={"task", "delete"},
     *     @OA\Parameter(
     *      in="path",
     *      name="id",
     *      required=true,
     *      example=123,
     *      description="ID of the task",
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
     * @param Task $task
     * @return Response
     * @throws Exception
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return \response(null, 200);
    }
}
