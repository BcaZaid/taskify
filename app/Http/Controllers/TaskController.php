<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'task' => 'required|string|max:255',
        ]);

        // Create a new task
        Task::create([
            'task' => $request->task,
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Task added successfully!');
    }
    public function index()
    {
        $tasks = Task::orderBy('created_at', 'asc')->get();
        return view('tasks.index', compact('tasks'));
    }
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }
    public function update(Request $request, Task $task)
    {
        // Validate the request
        $request->validate([
            'task' => 'required|string|max:255',
        ]);

        // Update the task
        $task->update([
            'task' => $request->task,
        ]);

        // Redirect back with a success message
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }
    public function destroy(Task $task)
    {
        // Delete the task
        $task->delete();

        // Redirect back with a success message
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }
    public function reorder(Request $request)
{
    $order = $request->input('order');

    foreach ($order as $index => $id) {
        $task = Task::find($id);
        $task->order = $index;
        $task->save();
    }

    return response()->json(['success' => true]);
}

}
