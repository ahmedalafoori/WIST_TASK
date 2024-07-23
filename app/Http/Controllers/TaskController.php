<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tasks = Auth::user()->tasks ?? collect(); // Use collect() to ensure it's an empty collection if null
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string', // التحقق من صحة الحقل الوصفي

        ]);

        // إنشاء مهمة جديدة وتعيين القيم
        $task = new Task([
            'title' => $request->input('title'),
            'description' => $request->input('description'), // تعيين الحقل الوصفي

        ]);

        // حفظ المهمة في قاعدة البيانات
        Auth::user()->tasks()->save($task);

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }


    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        return redirect()->route('tasks.index');
    }

    public function markAsDone(Task $task)
    {
        $this->authorize('update', $task);
        $task->update(['is_done' => true]);

        return redirect()->route('tasks.index');
    }
}
