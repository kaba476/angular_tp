<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskRessource;
use App\Models\Task;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Auth::user()->tasks;
        return TaskRessource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        try {
            $task = Auth::user()->tasks()->create($request->validated());
            return new TaskRessource($task);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        try {
            $this->authorize('view', $task);
            return new TaskRessource($task);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'You are not authorized to view this task',
                'error' => $e->getMessage()
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving the task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            $this->authorize('update', $task);
            $task->update($request->validated());
            return new TaskRessource($task);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'You are not authorized to update this task',
                'error' => $e->getMessage()
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {
            $this->authorize('delete', $task);
            $task->delete();
            return response()->noContent();
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'You are not authorized to delete this task',
                'error' => $e->getMessage()
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the task',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
