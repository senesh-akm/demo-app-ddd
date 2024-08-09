<?php

namespace App\Http\Controllers;

use App\Domain\Task\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        $tasks = $this->taskService->getAllTasks();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $this->taskService->createTask($request->title, $request->description);
        return redirect()->route('tasks.index');
    }

    public function updateStatus(Request $request, $id)
    {
        $task = $this->taskService->getTaskById($id);

        if ($task) {
            $task->setStatus($request->input('status'));
            $this->taskService->updateTask($task); // Make sure you have this method to handle updating a task
        }

        return redirect()->back();
    }

    public function complete($id)
    {
        $this->taskService->completeTask($id);
        return redirect()->route('tasks.index');
    }
}
