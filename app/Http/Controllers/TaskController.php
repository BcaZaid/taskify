<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'task' => 'required|string|max:255',
        ]);

        // Create a new task and associate it with the authenticated user
        Task::create([
            'task' => $request->task,
            'user_id' => auth()->id(),
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Task added successfully!');
    }

    /**
     * Display a listing of the tasks.
     */
    public function index()
    {
        // Retrieve tasks associated with the authenticated user, ordered by 'order'
        $tasks = Task::where('user_id', auth()->id())->orderBy('order')->get();

        // Return the tasks index view with the retrieved tasks
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task)
    {
        // Return the task edit view with the specified task
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task)
    {
        // Validate the request
        $request->validate([
            'task' => 'required|string|max:255',
        ]);

        // Update the task with the new data
        $task->update([
            'task' => $request->task,
        ]);

        // Redirect back with a success message
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        // Delete the task
        $task->delete();

        // Redirect back with a success message
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }

    /**
     * Reorder tasks based on the new order provided.
     */
    public function reorder(Request $request)
    {
        // Get the new order from the request
        $order = $request->input('order');

        // Update each task's order
        foreach ($order as $index => $id) {
            $task = Task::find($id);
            $task->order = $index;
            $task->save();
        }

        // Return a success response
        return response()->json(['success' => true]);
    }

    /**
     * Toggle the completion status of a task.
     */
    public function toggleTask($id)
    {
        // Find the task by ID
        $task = Task::findOrFail($id);

        // Toggle the completed status
        $task->completed = !$task->completed;

        // Save the updated task
        $task->save();

        // Return a response with the new completion status
        return response()->json(['success' => true, 'completed' => $task->completed]);
    }
}
