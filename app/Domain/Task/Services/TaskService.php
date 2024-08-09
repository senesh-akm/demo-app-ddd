<?php

namespace App\Domain\Task\Services;

use App\Domain\Task\Entities\Task;
use App\Domain\Task\Repositories\TaskRepositoryInterface;

class TaskService
{
    private $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function createTask($title, $description)
    {
        $task = new Task($title, $description);
        $this->taskRepository->save($task);
    }

    public function getAllTasks()
    {
        return $this->taskRepository->findAll();
    }

    public function getTaskById($id)
    {
        return $this->taskRepository->findById($id);
    }

    public function setTaskStatus($id, $status)
    {
        $task = $this->taskRepository->findById($id);
        if ($task) {
            $task->setStatus($status);
            $this->taskRepository->save($task);
        }
    }

    public function completeTask($id)
    {
        $task = $this->taskRepository->findById($id);
        if ($task) {
            $task->markAsCompleted();
            $this->taskRepository->save($task);
        }
    }

    public function updateTask(Task $task)
    {
        $this->taskRepository->save($task);
    }
}
