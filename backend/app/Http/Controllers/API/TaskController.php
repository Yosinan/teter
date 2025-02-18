<?php

namespace App\Http\Controllers\API;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $tasks = Task::all();
        } else {
            $tasks = Task::where('user_id', $user->id)->get();
        }

        return response()->json($tasks);
    }

    public function store(Request $request)
    {

         // check missing data
        if (empty($request->deadline)) {
            return response()->json(['message' => 'Deadline is required'], 422);
        }

        if (empty($request->title)) {
            return response()->json(['message' => 'Title is required'], 422);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,completed,in_progress',
            'priority' => 'nullable|in:low,medium,high',
            'deadline' => 'required|date',
        ]);

    
        $task = Task::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? 'pending',
            'priority' => $request->priority ?? 'medium',
            'deadline' => $request->deadline,
        ]);

        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $task->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:pending,completed,in_progress',
            'priority' => 'sometimes|in:low,medium,high',
            'deadline' => 'sometimes|date',
        ]);

        $task->update($request->only(['title', 'description', 'status', 'priority', 'deadline']));

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $task->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}